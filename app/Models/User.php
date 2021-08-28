<?php

namespace App\Models;

use App\Events\UserSaving;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\MailResetPasswordToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'pre_name',
        'last_name',
        'profile_picture',
        'email',
        'password',
        'email_verification_code',
        'subscribed_newsletter',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Activities
     */
    public function sendActivity($short, $details = '', $attributes = []) {
        Activity::create([
            'issuer_type' => 1, // 1 => User
            'issuer_id' => $this->id,
            'short' => $short,
            'details' => $details,
            'attributes' => json_encode($attributes)
        ]);
    }

    /**
     * Override reset password notification
     * @param string $token
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new MailResetPasswordToken($token));
    }

    /**
     * Override confirm email notification
     */
    public function sendEmailVerificationNotification() {
        $this->notify(new EmailVerificationNotification($this->email_verification_code));
    }

    /**
     * Role Relation
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Badges Relation
     */
    public function relations(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    /**
     * User has permission
     */
    public function hasPermission(string $permission): bool {
        if ($this->role->relations()->where('name', $permission)->first()) {
            return true;
        }

        return false;
    }

    /**
     * Creator relations
     */
    public function created_permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'user_id');
    }

    public function created_badges(): HasMany
    {
        return $this->hasMany(Badge::class, 'user_id');
    }

    public function created_roles(): HasMany
    {
        return $this->hasMany(Role::class, 'user_id');
    }

    public function created_categories(): HasMany
    {
        return $this->hasMany(Category::class, 'user_id');
    }

    public function created_posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function created_post_histories(): HasMany
    {
        return $this->hasMany(PostHistory::class, 'user_id');
    }

    public function post_votes(): HasMany
    {
        return $this->hasMany(PostVote::class, 'user_id');
    }

    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class, 'target_id');
    }

    public function staff_bans(): HasMany
    {
        return $this->hasMany(Ban::class, 'staff_id');
    }
}
