<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'is_category',
        'category_id',
        'is_post',
        'post_id',
        'title',
        'file_name',
        'expires_at',
        'password',
        'max_downloads'
    ];

    protected $dates = [
        'expires_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): ?BelongsTo
    {
        if ($this->is_category) return $this->belongsTo(Category::class);
        return null;
    }

    public function post(): ?BelongsTo
    {
        if ($this->is_post) return $this->belongsTo(Post::class);
        return null;
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DocumentDownload::class);
    }
}
