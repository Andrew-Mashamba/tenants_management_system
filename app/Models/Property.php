<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'property_type',
        'city',
        'state',
        'postal_code',
        'country',
        'description',
        'amenities',
        'images',
        'type',
        'status',
        'total_units',
        'available_units',
        'owner_id',
        'settings',
        'default_currency',
        'timezone',
        'document_categories',
        'landlord_id',
        'agent_id',
        // 'latitude',
        // 'longitude',
    ];

    protected $casts = [
        'settings' => 'array',
        'document_categories' => 'array',
        'amenities' => 'array',
        'images' => 'array',
    ];

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function paymentSettings(): HasOne
    {
        return $this->hasOne(PaymentSetting::class);
    }

    public function regionalSettings(): HasOne
    {
        return $this->hasOne(RegionalSetting::class);
    }

    public function getSetting($key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
        return $this;
    }

    public function getDocumentCategories()
    {
        return $this->document_categories ?? [
            'lease_agreements',
            'property_photos',
            'inspection_reports',
            'maintenance_records',
            'insurance_documents',
            'permits',
            'other'
        ];
    }

    public function addDocumentCategory($category)
    {
        $categories = $this->document_categories ?? [];
        if (!in_array($category, $categories)) {
            $categories[] = $category;
            $this->document_categories = $categories;
            $this->save();
        }
        return $this;
    }

    public function removeDocumentCategory($category)
    {
        $categories = $this->document_categories ?? [];
        $categories = array_diff($categories, [$category]);
        $this->document_categories = array_values($categories);
        $this->save();
        return $this;
    }
}
