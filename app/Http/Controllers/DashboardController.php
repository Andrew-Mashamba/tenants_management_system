<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Activity;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalProperties' => Property::count(),
            'activeTenants' => Tenant::where('status', 'active')->count(),
            'pendingPayments' => Payment::where('status', 'pending')->count(),
            'openMaintenanceRequests' => MaintenanceRequest::where('status', 'open')->count(),
            'recentActivities' => Activity::latest()->take(5)->get(),
            'upcomingEvents' => Event::where('date', '>=', now())->orderBy('date')->take(5)->get(),
        ]);
    }
} 