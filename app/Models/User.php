<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_photo',
        'two_factor_enabled',
        'last_login_at',
        'phone_number',
        'city',
        'state',
        'country',
        'postal_code',
        'profile_photo_path',
        'bio',
        'preferences',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'preferences' => 'array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching($role);
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        $this->roles()->detach($role);
    }

    public function enableTwoFactorAuth()
    {
        $this->two_factor_enabled = true;
        $this->two_factor_confirmed_at = now();
        $this->save();
    }

    public function disableTwoFactorAuth()
    {
        $this->two_factor_enabled = false;
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_confirmed_at = null;
        $this->save();
    }

    public function generateTwoFactorSecret()
    {
        $this->two_factor_secret = Str::random(32);
        $this->save();
        return $this->two_factor_secret;
    }

    public function generateRecoveryCodes()
    {
        $codes = collect(range(1, 8))->map(function () {
            return Str::random(10) . '-' . Str::random(10);
        })->toArray();

        $this->two_factor_recovery_codes = json_encode($codes);
        $this->save();

        return $codes;
    }

    public function hasTwoFactorEnabled()
    {
        return $this->two_factor_enabled && $this->two_factor_confirmed_at !== null;
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function updateProfile(array $data)
    {
        $this->update([
            'name' => $data['name'] ?? $this->name,
            'email' => $data['email'] ?? $this->email,
            'phone' => $data['phone'] ?? $this->phone,
            'address' => $data['address'] ?? $this->address,
            'city' => $data['city'] ?? $this->city,
            'state' => $data['state'] ?? $this->state,
            'country' => $data['country'] ?? $this->country,
            'postal_code' => $data['postal_code'] ?? $this->postal_code,
            'bio' => $data['bio'] ?? $this->bio,
            'preferences' => $data['preferences'] ?? $this->preferences,
        ]);

        if (isset($data['password'])) {
            $this->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        return $this;
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class, 'tenant_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'tenant_id');
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class, 'tenant_id');
    }

    public function kycVerification(): HasOne
    {
        return $this->hasOne(TenantKycVerification::class, 'tenant_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TenantDocument::class, 'tenant_id');
    }

    public function coTenants(): HasMany
    {
        return $this->hasMany(CoTenant::class, 'primary_tenant_id');
    }

    public function onboardingWorkflow(): HasOne
    {
        return $this->hasOne(TenantOnboardingWorkflow::class, 'tenant_id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

    public function managedProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'agent_id');
    }

    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    public function isTenant(): bool
    {
        return $this->hasRole('tenant');
    }

    public function isLandlord(): bool
    {
        return $this->hasRole('landlord');
    }

    public function isPropertyManager(): bool
    {
        return $this->hasRole('property_manager');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }
}
