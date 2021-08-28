<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'color',
        'content',
        'user_id',
        'type',
        'icon',
        'target_id',
        'seen'
    ];

    /**
     * User relation
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function target() {
        return $this->belongsTo(User::class, 'target_id');
    }
}
