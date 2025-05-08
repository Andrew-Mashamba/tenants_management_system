<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'changes_description',
        'created_by',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($version) {
            $latestVersion = self::where('document_id', $version->document_id)
                ->orderBy('version_number', 'desc')
                ->first();

            $version->version_number = $latestVersion 
                ? self::incrementVersion($latestVersion->version_number)
                : '1.0';
        });
    }

    private static function incrementVersion(string $version): string
    {
        $parts = explode('.', $version);
        $parts[1] = (int)$parts[1] + 1;
        return implode('.', $parts);
    }
} 