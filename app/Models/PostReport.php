<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostReport extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'active'
    ];

    /**
     * Post relation
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }

    /**
     * User relation
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
