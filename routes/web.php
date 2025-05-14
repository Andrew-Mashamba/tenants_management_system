<?php

use App\Livewire\Auth\Register;
use App\Livewire\Properties\PropertyList;
use App\Livewire\Properties\PropertyForm;
use App\Livewire\Tenants\LeaseList;
use App\Livewire\Tenants\LeaseForm;
use App\Livewire\Tenants\TenantForm;
use App\Livewire\Tenants\LeaseTermination;
use App\Livewire\Tenants\TenantList;
use App\Livewire\Billing\InvoiceList;
use App\Livewire\Billing\InvoiceForm;
use App\Livewire\Billing\InvoiceShow;
use App\Livewire\Payments\PaymentList;
use App\Livewire\Payments\PaymentForm;
use App\Livewire\Payments\PaymentShow;
use App\Livewire\Maintenance\MaintenanceRequestList;
use App\Livewire\Maintenance\MaintenanceRequestForm;
use App\Livewire\Maintenance\VendorList;
use App\Livewire\Maintenance\VendorForm;
use App\Livewire\Communication\MessageList;
use App\Livewire\Communication\MessageForm;
use App\Livewire\Reports\ReportList;
use App\Livewire\Documents\DocumentList;
use App\Livewire\Documents\DocumentForm;
use App\Livewire\Settings\SettingsForm;
use App\Livewire\Leases\LeaseWorkflowForm;
use App\Livewire\Leases\LeaseTemplateForm;
use App\Livewire\Leases\LeaseDocumentForm;
use App\Livewire\Activities\ActivityList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Properties\PropertyDocumentController;
use App\Http\Controllers\Properties\AccountingIntegrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Leases\LeaseTemplateController;
use App\Livewire\Properties\PropertyShow;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Activities Module
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', function () {
            return view('activities.index');
        })->name('index');
    });

    // Properties Module
    Route::prefix('properties')->name('properties.')->group(function () {
        
        Route::get('/', function () {
            return view('properties.index');
        })->name('index');
        Route::get('/create', function () {
            return view('properties.create');
        })->name('create');

        // Route::get('/create', PropertyForm::class)->name('create');
        
        Route::get('/{property}/edit', PropertyForm::class)->name('edit');

        Route::get('/{property}/show', PropertyShow::class)->name('show');


    });

    // Billing Module
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', function () {
            return view('billing.index');
        })->name('index');
        Route::get('/create', function () {
            return view('billing.create');
        })->name('create');
        // Route::get('/create', InvoiceForm::class)->name('create');
       
    });

    //Invoices Module
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/{invoice}/edit', InvoiceForm::class)->name('edit');

        Route::get('/{invoice}/show', InvoiceShow::class)->name('show');        
    });

    // Payments Module
    Route::prefix('payments')->name('payments.')->group(function () {
        // dd('djdkfdfd');
        Route::get('/', function () {
            return view('payments.index');
        })->name('index');
        Route::get('/create', function () {
            return view('payments.create');
        })->name('create');
        // Route::get('/{payment}/show', function () {
        //     return view('payments.show');
        // })->name('show');
        // Route::get('/create', PaymentForm::class)->name('create');
        Route::get('/{payment}/show', PaymentShow::class)->name('show');
        Route::get('/{payment}/edit', PaymentForm::class)->name('edit');
    });

    // Maintenance Module
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', function () {
            return view('maintenance.index');
        })->name('index');
        Route::get('/requests/create', MaintenanceRequestForm::class)->name('requests.create');
        Route::get('/requests/{request}/edit', MaintenanceRequestForm::class)->name('requests.edit');
        Route::get('/vendors', VendorList::class)->name('vendors.index');
        Route::get('/vendors/create', VendorForm::class)->name('vendors.create');
        Route::get('/vendors/{vendor}/edit', VendorForm::class)->name('vendors.edit');
    });

    // Communication Module
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/', function () {
            return view('communication.index');
        })->name('index');
        Route::get('/create', MessageForm::class)->name('create');
        Route::get('/{message}/edit', MessageForm::class)->name('edit');
    });

    // Reports Module
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('reports.index');
        })->name('index');
    });

    // Documents Module
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', function () {
            return view('documents.index');
        })->name('index');
        Route::get('/create', DocumentForm::class)->name('create');
        Route::get('/{document}/edit', DocumentForm::class)->name('edit');
    });

    // Settings Module
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('settings.index');
        })->name('index');
    });

    // Leases Module
    Route::prefix('leases')->name('leases.')->group(function () {
        Route::get('/', function () {
            return view('leases.index');
        })->name('index');
        Route::get('/create', LeaseForm::class)->name('create');
        // Route::get('/{lease}/edit', LeaseForm::class)->name('edit');
        Route::get('/{lease}/terminate', LeaseTermination::class)->name('terminate');
        Route::get('/{lease}/workflows/create', LeaseWorkflowForm::class)->name('workflows.create');
        Route::get('/{lease}/workflows/{workflow}/edit', LeaseWorkflowForm::class)->name('workflows.edit');
        Route::get('/templates', LeaseTemplateForm::class)->name('templates.index');
        Route::get('/{lease}/documents/create', LeaseDocumentForm::class)->name('documents.create');
        Route::get('/{lease}/documents/{document}/edit', LeaseDocumentForm::class)->name('documents.edit');
    });

    // Tenants Module
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', function () {
            return view('tenants.index');
        })->name('index');
        Route::get('/create', TenantForm::class)->name('create');
        Route::get('/{tenant}/edit', TenantForm::class)->name('edit');
    });

    // Two-Factor Authentication Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'show'])
            ->name('two-factor.show');
        Route::post('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'enable'])
            ->name('two-factor.enable');
        Route::delete('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'disable'])
            ->name('two-factor.disable');
        Route::post('/two-factor-challenge', [TwoFactorAuthenticationController::class, 'verify'])
            ->name('two-factor.verify');
        Route::post('/two-factor-recovery', [TwoFactorAuthenticationController::class, 'recovery'])
            ->name('two-factor.recovery');
    });

    // Lease Template Routes
    Route::middleware(['auth'])->group(function () {
        Route::resource('lease-templates', LeaseTemplateController::class);
    });
});

// Password Reset Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->name('password.update');
});
