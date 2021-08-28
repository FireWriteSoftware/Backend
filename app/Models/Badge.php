<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Badge extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'icon',
        'color',
        'is_role_badge',
        'role_id',
        'user_id'
    ];

    /**
     * Role Relation
     */
    public function role(): ?BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User relation
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }

    /**
     * User relation
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
