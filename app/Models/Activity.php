<?php

namespace App\Models;

use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes, MassPrunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'issuer_id',
        'short',
        'details',
        'attributes'
    ];

    public function prunable()
    {
        return static::where('created_at', '<', now()->subDays(14));
    }
}
