<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(DocumentCategory::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = str($category->name)->slug();
        });
    }
} 