<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_type',
        'email_enabled',
        'sms_enabled',
        'push_enabled',
        'preferences'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'preferences' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCustomSettingsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setCustomSettingsAttribute($value)
    {
        $this->attributes['custom_settings'] = json_encode($value);
    }

    public function isChannelEnabled(string $channel): bool
    {
        $attribute = "{$channel}_enabled";
        return $this->$attribute ?? false;
    }

    public function getEnabledChannels(): array
    {
        $channels = [];
        if ($this->email_enabled) $channels[] = 'email';
        if ($this->sms_enabled) $channels[] = 'sms';
        if ($this->push_enabled) $channels[] = 'push';
        return $channels;
    }

    public function getCustomSetting(string $key, $default = null)
    {
        return $this->custom_settings[$key] ?? $default;
    }
} 