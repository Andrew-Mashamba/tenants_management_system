<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmartLock;
use App\Models\Tenant;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Events\LockStatusUpdated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SmartLockController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $property = $request->user()->properties()->findOrFail($request->property_id);
        
        $locks = $property->smartLocks()
            ->with(['authorizedUsers', 'accessLogs' => function ($query) {
                $query->latest()->take(10);
            }])
            ->get();

        return response()->json($locks);
    }

    public function store(Request $request)
    {
        $property = $request->user()->properties()->findOrFail($request->property_id);
        
        $this->authorize('create', [SmartLock::class, $property]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'device_id' => ['required', 'string', 'unique:smart_locks'],
            'provider' => ['required', 'string', 'in:august,yale,schlage'],
            'metadata' => ['nullable', 'array'],
        ]);

        $lock = $property->smartLocks()->create($validated);

        return response()->json($lock, 201);
    }

    public function show(SmartLock $lock)
    {
        $this->authorize('view', $lock);

        $lock->load(['authorizedUsers', 'accessLogs' => function ($query) {
            $query->latest()->take(10);
        }]);

        return response()->json($lock);
    }

    public function update(Request $request, SmartLock $lock)
    {
        $this->authorize('update', $lock);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'in:online,offline,maintenance'],
            'metadata' => ['sometimes', 'array'],
        ]);

        $lock->update($validated);

        return response()->json($lock);
    }

    public function destroy(SmartLock $lock)
    {
        $this->authorize('delete', $lock);

        $lock->delete();

        return response()->json(null, 204);
    }

    public function control(Request $request, SmartLock $lock)
    {
        $this->authorize('control', $lock);

        $validated = $request->validate([
            'action' => ['required', 'string', 'in:lock,unlock,check_status'],
        ]);

        // Check if lock is online
        if (!$lock->isOnline()) {
            return response()->json(['error' => 'Lock is offline'], 503);
        }

        // Check rate limiting
        $key = "lock_control:{$lock->id}:{$request->user()->id}";
        if (Cache::has($key)) {
            return response()->json(['error' => 'Too many requests'], 429);
        }
        Cache::put($key, true, 5); // 5 second cooldown

        try {
            // Log the access attempt
            $log = AccessLog::create([
                'smart_lock_id' => $lock->id,
                'user_id' => $request->user()->id,
                'access_type' => $validated['action'],
                'status' => 'pending',
                'metadata' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // Send command to lock
            $result = $this->sendLockCommand($lock, $validated['action']);

            // Update log with result
            $log->update([
                'status' => $result['success'] ? 'success' : 'failed',
                'metadata' => array_merge($log->metadata, [
                    'result' => $result,
                ]),
            ]);

            // Broadcast real-time update
            broadcast(new LockStatusUpdated($lock, $result))->toOthers();

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("Lock control error: {$e->getMessage()}", [
                'lock_id' => $lock->id,
                'user_id' => $request->user()->id,
                'action' => $validated['action'],
            ]);

            return response()->json(['error' => 'Failed to control lock'], 500);
        }
    }

    public function logs(SmartLock $lock)
    {
        $this->authorize('viewLogs', $lock);

        $logs = $lock->accessLogs()
            ->with('user')
            ->latest()
            ->paginate(20);

        return response()->json($logs);
    }

    protected function sendLockCommand(SmartLock $lock, string $action): array
    {
        // This would be implemented based on the specific lock provider
        // For now, we'll simulate a successful response
        return [
            'success' => true,
            'action' => $action,
            'timestamp' => now()->toIso8601String(),
            'lock_status' => $action === 'check_status' ? 'locked' : null,
        ];
    }

    public function grantAccess(Request $request, SmartLock $smartLock)
    {
        $this->authorize('update', $smartLock);

        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'access_method' => 'required|string|in:key,code,app',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tenant = Tenant::findOrFail($request->tenant_id);
        $smartLock->grantAccess($tenant, $request->access_method);

        return response()->json(['message' => 'Access granted successfully']);
    }

    public function revokeAccess(Request $request, SmartLock $smartLock)
    {
        $this->authorize('update', $smartLock);

        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tenant = Tenant::findOrFail($request->tenant_id);
        $smartLock->revokeAccess($tenant);

        return response()->json(['message' => 'Access revoked successfully']);
    }

    public function accessLogs(SmartLock $smartLock)
    {
        $this->authorize('view', $smartLock);

        $logs = AccessLog::where('smart_lock_id', $smartLock->id)
            ->with('tenant')
            ->orderBy('access_time', 'desc')
            ->paginate(20);

        return response()->json($logs);
    }
} 