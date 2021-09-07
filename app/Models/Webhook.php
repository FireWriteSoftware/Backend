<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Webhook extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'type',
        'url'
    ];

    public function scopes(): HasMany
    {
        return $this->hasMany(WebhookScope::class);
    }

    public function routeNotificationForDiscord(): string
    {
        if ($this->type === 'discord') {
            return $this->url;
        }

        return '';
    }
}
