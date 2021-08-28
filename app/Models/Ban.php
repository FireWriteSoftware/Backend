<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target_id',
        'staff_id',
        'reason',
        'description',
        'ban_until',
        'type'
    ];

    /**
     * Target relation
     */
    public function target() {
        return $this->belongsTo(User::class, 'target_id');
    }

    /**
     * Target relation
     */
    public function staff() {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Is ban active?
     */
    public function is_active(): bool {
        if (is_null($this->ban_until)) return true;

        $now = new DateTime();
        $date = new DateTime($this->ban_until);
        $diff = $date->getTimestamp() - $now->getTimestamp();
        return $diff > 0;
    }
}
