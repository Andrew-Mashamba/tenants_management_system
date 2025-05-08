<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyCustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'field_id',
        'value',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(PropertyCustomField::class, 'field_id');
    }

    public function getFormattedValueAttribute()
    {
        return $this->field->getFormattedValue($this->value);
    }
} 