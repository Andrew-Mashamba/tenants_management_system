<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Tenants\KycVerificationForm;
use App\Livewire\Tenants\CoTenantForm;
use App\Livewire\Tenants\OnboardingWorkflow;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('tenants.kyc-verification-form', KycVerificationForm::class);
        Livewire::component('tenants.co-tenant-form', CoTenantForm::class);
        Livewire::component('tenants.onboarding-workflow', OnboardingWorkflow::class);
    }
}
