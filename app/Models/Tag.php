<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
        'user_id'
    ];

    /**
     * User relation
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Posts & Sub-Categories Relations
     */
    public function posts() {
        return $this->hasMany(PostTag::class);
    }
}
