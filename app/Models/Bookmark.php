<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'is_category',
        'category_id',
        'is_post',
        'post_id'
    ];

    /**
     * User relation
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Category relation
     */
    public function category() {
        if (!$this->is_category) return null;
        return $this->belongsTo(Category::class);
    }

    /**
     * Post Relation
     */
    public function post() {
        if (!$this->is_post) return null;
        return $this->belongsTo(Post::class);
    }
}
