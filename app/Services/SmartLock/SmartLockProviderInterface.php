<?php

namespace App\Services\SmartLock;

interface SmartLockProviderInterface
{
    public function lock(): array;
    public function unlock(): array;
    public function checkStatus(): array;
} 