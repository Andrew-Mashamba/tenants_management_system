<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Broadcast::routes();

        Broadcast::channel('property.{propertyId}.locks', function ($user, $propertyId) {
            return $user->properties()->where('properties.id', $propertyId)->exists();
        });
    }
} 