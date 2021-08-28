<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:name',
            'name' => 'required_without:email',
            'password' => 'required'
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        if (Auth::attempt($request->all())) {
            $user = Auth::user();

            $active_bans = $user->bans()->where(['type' => 0])->get()->filter(function ($b) {
                if ($b->is_active()) {
                    return $b;
                }
            });

            if (sizeof($active_bans) > 0) {
                return $this->sendError(
                    "User has global ban",
                    [
                        'banned' => true,
                        'bans' => $active_bans
                    ],
                    403
                );
            }

            $now = now();
            $user->sendActivity('Successful login.', "$user->name [$user->id] logged in on $now", $user);

            return $this->sendResponse([
                'token' => $user->createToken('PersonalAccessToken')->accessToken,
                'user' => $user->only([
                    'id',
                    'name',
                    'pre_name',
                    'last_name',
                    'email',
                    'role_id',
                    'profile_picture'
                ])
            ], 'User login successfully.');
        }

        Activity::create([
            'issuer_type' => 0, // 0 => Unknown/Undefined
            'issuer_id' => 1,
            'short' => 'Failed login.',
            'details' => "{$request->ip()} failed to log in into account",
            'attributes' => json_encode($request->all())
        ]);

        return $this->sendError('Unauthorised.', ['error' => 'Login failed.']);
    }

    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'pre_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['email_verification_code'] = Str::random(40);
        $input['role_id'] = Role::where('is_default', 1)->first()->id;
        $user = User::create($input);

        $user->sendActivity('User Account has been created via registering.', '', $user);
        $user->sendEmailVerificationNotification();
        $user->sendActivity('Email-Verification-Email has been sent.', 'A mail to verify the email-adress has been sent to ' . $user->email, $user);


        $success['token'] =  $user->createToken('PersonalAccessToken')->accessToken;
        $user->sendActivity('Session token has been created.', 'A session token for login and api requests has been created and passed.');

        $success['user'] =  $user->only([
            'id',
            'name',
            'pre_name',
            'last_name',
            'email',
            'role_id',
            'profile_picture'
        ]);

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Get current user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        if (Auth::user() == null) {
            Activity::create([
                'issuer_type' => 0, // 0 => Unknown/Undefined
                'issuer_id' => 1,
                'short' => 'Tried to fetch unauthenticated user',
                'details' => "IP {$request->ip()} tried to fetch his user, but is unauthenticated.",
                'attributes' => json_encode($request)
            ]);
        }

        return $this->sendResponse([
            'user' => Auth::user() ?? null
        ], 'User returned successfully.');
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::user()->sendActivity('Logged out', 'User has logged out and token has been revoked.');
        Auth::user()->token()->revoke();
        return $this->sendResponse([], 'User Logged Out');
    }

    /**
     * Recover password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function recover_password(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $input = $request->all();
        $user = User::where('email', $input['email'])->first();

        if (!$user->exists()) {
            return $this->sendError('This email does not belong to any users', ['email' => $input['email']]);
        }

        $token = Str::random(40);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $user->sendPasswordResetNotification($token);

        $user->sendActivity('Password-Reset-Email has been sent.', 'A mail to reset the password has been sent to ' . $user->email, $user);

        return $this->sendResponse([], 'Mail has been sent');
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset_password(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$tokenData) return $this->sendError('Invalid token.', [
            'errors' => []
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->sendError('Invalid token.', [
            'errors' => []
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $user->email)->delete();

        return $this->sendResponse([], 'Successfully resetted password.');
    }

    /**
     * Change password
     * @param Request $request
     * @return JsonResponse
     */
    public function change_password(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $user = Auth::user();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return $this->sendError('Invalid credentials.', []);
        }

        $user->password = Hash::make($request['password']);
        $user->save();
        $user->sendActivity('Password-Reset has been performed', 'The password has been changed through the profile or an admin.');

        return $this->sendResponse([], 'Password changed successfully');
    }

    /**
     * Confirm email address
     * @param Request $request
     * @return JsonResponse
     */
    public function confirm_email(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $user = User::where('email_verification_code', $request->input('token'))->first();

        if (!$user) {
            return $this->sendError('Invalid token.', []);
        }

        $user->email_verification_code = '';
        $user->email_verified_at = now();
        $user->save();
        $user->sendActivity('Email-Verification passed', 'The email has been verified trough the email-verification.');

        return $this->sendResponse([], 'Email confirmed successfully');
    }

    public function update_details(Request $request, $account_id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name,' . $account_id,
            'pre_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $account_id,
            'profile_picture' => ''
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $account = User::find($account_id);

        if (is_null($account)) {
            return $this->sendError('Invalid user', ['user_id' => $account_id]);
        }

        $account->update($request->all());
        $account->sendActivity('Account details has been changed', 'The profile details has been changed through the profile or an admin');

        return $this->sendResponse($account, 'Successfully updated user details.');
    }
}
