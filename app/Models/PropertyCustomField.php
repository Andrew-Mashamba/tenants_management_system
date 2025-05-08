<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyCustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'options',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(PropertyCustomFieldValue::class, 'field_id');
    }

    public function getOptionsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }

    public function validateValue($value): bool
    {
        if ($this->is_required && empty($value)) {
            return false;
        }

        switch ($this->type) {
            case 'text':
                return is_string($value);
            case 'number':
                return is_numeric($value);
            case 'date':
                return strtotime($value) !== false;
            case 'select':
                return in_array($value, $this->options);
            case 'checkbox':
                return is_bool($value);
            default:
                return true;
        }
    }

    public function getFormattedValue($value)
    {
        switch ($this->type) {
            case 'date':
                return date('Y-m-d', strtotime($value));
            case 'number':
                return number_format($value, 2);
            case 'checkbox':
                return $value ? 'Yes' : 'No';
            default:
                return $value;
        }
    }
} 