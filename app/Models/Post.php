<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title',
        'content',
        'thumbnail',
        'user_id',
        'category_id',
        'approved_by',
        'approved_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['approved_at'];

    /**
     * User relation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approved_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Parent category relation
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Tags relation
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    /**
     * Post relations
     */
    public function histories() {
        return $this->hasMany(PostHistory::class);
    }

    public function votes() {
        return $this->hasMany(PostVote::class);
    }

    public function comments() {
        return $this->hasMany(PostComment::class);
    }

    public function is_bookmarked() {
        return Bookmark::where('user_id', auth()->user()->id)->where('is_post', true)->where('post_id', $this->id)->exists();
    }
}
