<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
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
        'thumbnail',
        'user_id',
        'parent_id'
    ];

    /**
     * User relation
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Children
     */
    public function subcategory() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Posts
     */
    public function posts() {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function is_bookmarked() {
        return Bookmark::where('user_id', auth()->user()->id)->where('is_category', true)->where('category_id', $this->id)->exists();
    }
}
