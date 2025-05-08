<?php

namespace App\Services\SmartLock;

use App\Models\SmartLock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AugustLockService implements SmartLockProviderInterface
{
    protected $apiUrl = 'https://api-production.august.com';
    protected $lock;

    public function __construct(SmartLock $lock)
    {
        $this->lock = $lock;
    }

    public function lock(): array
    {
        return $this->sendCommand('lock');
    }

    public function unlock(): array
    {
        return $this->sendCommand('unlock');
    }

    public function checkStatus(): array
    {
        return $this->sendCommand('status');
    }

    protected function sendCommand(string $action): array
    {
        try {
            $response = Http::withHeaders([
                'x-august-api-key' => config('services.august.api_key'),
                'x-kease-api-key' => config('services.august.api_key'),
                'Content-Type' => 'application/json',
                'Accept-Version' => '0.0.1',
                'User-Agent' => 'August/Luna-6.3.4',
            ])->post("{$this->apiUrl}/remoteoperate/{$this->lock->device_id}/{$action}", [
                'command' => $action,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'action' => $action,
                    'timestamp' => now()->toIso8601String(),
                    'lock_status' => $this->getLockStatus($response->json()),
                ];
            }

            Log::error("August lock command failed", [
                'lock_id' => $this->lock->id,
                'action' => $action,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'action' => $action,
                'error' => 'Failed to execute command',
            ];
        } catch (\Exception $e) {
            Log::error("August lock error: {$e->getMessage()}", [
                'lock_id' => $this->lock->id,
                'action' => $action,
            ]);

            return [
                'success' => false,
                'action' => $action,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function getLockStatus(array $response): string
    {
        return match($response['status'] ?? '') {
            'locked' => 'locked',
            'unlocked' => 'unlocked',
            default => 'unknown',
        };
    }
} 