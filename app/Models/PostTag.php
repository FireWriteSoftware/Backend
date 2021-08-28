<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostTag extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'post_id',
        'tag_id',
    ];

    /**
     * Post relation
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }

    /**
     * Tag relation
     */
    public function tag() {
        return $this->belongsTo(Tag::class);
    }
}
