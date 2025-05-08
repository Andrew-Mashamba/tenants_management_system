<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceStatusUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_request_id',
        'user_id',
        'status',
        'notes',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 