<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'login_success' => 'Successfully logged in.',
    'login_failed' => 'Failed to login in.',
    'register_success' => 'Successfully created an account.',
    'unauthenticated' => 'User is not logged in yet.',
    'already_authed' => 'User is already logged in.',
    'get_details' => 'Successfully fetched user details.',
    'logged_out' => 'Successfully logged out of account.',
    'invalid_email' => 'This email doesn\' belongs to any account.',
    'invalid_reset_token' => 'Invalid token.',
    'invalid_password' => 'Invalid password provided.',
    'mail' => [
        'reset_password' => [
            'title' => 'Reset your password',
            'call_click' => 'Did you forget your password? Click the button to reset it.',
            'button' => 'Reset Password'
        ],
        'verify_mail' => [
            'title' => 'Verify Email Address',
            'call_click' => 'Please click the button below to verify your email address.',
            'button' => 'Verify Email Address',
            'not_you' => 'If you did not create an account, no further action is required.'
        ],
        'login_success' => [
            'title' => 'Login was successful',
            'subtitle' => 'Somebody just logged into your account.',
            'not_you' => 'If it was you, you can ignore this email. If not, please change your password immediately!',
            'button' => 'Login'
        ],
        'login_failed' => [
            'title' => 'Login failed',
            'subtitle' => 'Somebody just failed logging into your account.',
            'not_you' => 'If it was you, you can ignore this email. If not, please change your password immediately!',
            'button' => 'Login'
        ],
        'email_changed' => [
            'title' => 'Email has been changed',
            'subtitle' => 'You\'re account\'s email has been just changed.',
            'not_you' => 'If it was you, you can ignore this email. If not, please contact an administrator as soon as possible!',
            'button' => 'Login',
        ],
        'password_changed' => [
            'title' => 'Password has been changed',
            'subtitle' => 'You\'re account\'s password has been just changed.',
            'not_you' => 'If it was you, you can ignore this email. If not, please try to reset your password using the email method or contact an administrator as soon as possible!',
            'button' => 'Login'
        ],
    ]
];
