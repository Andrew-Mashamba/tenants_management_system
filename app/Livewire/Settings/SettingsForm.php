<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class SettingsForm extends Component
{
    public $settings = [];

    public function mount()
    {
        $this->settings = [
            'company_name' => config('app.company_name', ''),
            'company_email' => config('app.company_email', ''),
            'company_phone' => config('app.company_phone', ''),
            'company_address' => config('app.company_address', ''),
            'invoice_prefix' => config('app.invoice_prefix', 'INV-'),
            'currency' => config('app.currency', 'USD'),
            'timezone' => config('app.timezone', 'UTC'),
        ];
    }

    public function save()
    {
        $validated = $this->validate([
            'settings.company_name' => 'required|string|max:255',
            'settings.company_email' => 'required|email',
            'settings.company_phone' => 'required|string|max:20',
            'settings.company_address' => 'required|string',
            'settings.invoice_prefix' => 'required|string|max:10',
            'settings.currency' => 'required|string|size:3',
            'settings.timezone' => 'required|string|timezone',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Cache::forever('settings.' . $key, $value);
        }

        session()->flash('message', 'Settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.settings.settings-form');
    }
} 