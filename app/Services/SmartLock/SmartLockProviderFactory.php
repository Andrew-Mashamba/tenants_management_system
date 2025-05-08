<?php

namespace App\Services\SmartLock;

use App\Models\SmartLock;
use InvalidArgumentException;

class SmartLockProviderFactory
{
    public static function make(SmartLock $lock): SmartLockProviderInterface
    {
        return match($lock->provider) {
            'august' => new AugustLockService($lock),
            'yale' => new YaleLockService($lock),
            'schlage' => new SchlageLockService($lock),
            default => throw new InvalidArgumentException("Unsupported lock provider: {$lock->provider}"),
        };
    }
} 