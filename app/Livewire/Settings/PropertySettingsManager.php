<?php

namespace App\Livewire\Settings;

use App\Models\{
    Property,
    PaymentSetting,
    PropertyCustomField,
    NotificationPreference,
    RegionalSetting
};
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PropertySettingsManager extends Component
{
    public $property;
    public $activeTab = 'payment';

    // Payment Settings
    public $paymentGateway;
    public $gatewayCredentials;
    public $lateFeePercentage;
    public $gracePeriodDays;
    public $autoChargeLateFees;
    public $sendPaymentReminders;
    public $reminderDaysBefore;

    // Custom Fields
    public $newFieldName;
    public $newFieldType;
    public $newFieldOptions;
    public $newFieldRequired;

    // Notification Preferences
    public $emailEnabled;
    public $smsEnabled;
    public $pushEnabled;
    public $notificationTypes;

    // Regional Settings
    public $timezone;
    public $dateFormat;
    public $timeFormat;
    public $currency;
    public $language;
    public $numberFormat;
    public $firstDayOfWeek;

    protected $rules = [
        'paymentGateway' => 'required|string',
        'lateFeePercentage' => 'required|numeric|min:0|max:100',
        'gracePeriodDays' => 'required|integer|min:0',
        'autoChargeLateFees' => 'boolean',
        'sendPaymentReminders' => 'boolean',
        'reminderDaysBefore' => 'required|integer|min:1',
        'newFieldName' => 'required_if:activeTab,custom-fields|string|max:255',
        'newFieldType' => 'required_if:activeTab,custom-fields|string',
        'timezone' => 'required|string',
        'dateFormat' => 'required|string',
        'timeFormat' => 'required|string',
        'currency' => 'required|string',
        'language' => 'required|string',
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->loadPaymentSettings();
        $this->loadNotificationPreferences();
        $this->loadRegionalSettings();
    }

    public function loadPaymentSettings()
    {
        $settings = $this->property->paymentSettings;
        if ($settings) {
            $this->paymentGateway = $settings->payment_gateway;
            $this->gatewayCredentials = $settings->gateway_credentials;
            $this->lateFeePercentage = $settings->late_fee_percentage;
            $this->gracePeriodDays = $settings->grace_period_days;
            $this->autoChargeLateFees = $settings->auto_charge_late_fees;
            $this->sendPaymentReminders = $settings->send_payment_reminders;
            $this->reminderDaysBefore = $settings->reminder_days_before;
        }
    }

    public function loadNotificationPreferences()
    {
        $preferences = Auth::user()->notificationPreferences()->firstOrCreate([
            'notification_type' => 'property_' . $this->property->id
        ]);

        $this->emailEnabled = $preferences->email_enabled;
        $this->smsEnabled = $preferences->sms_enabled;
        $this->pushEnabled = $preferences->push_enabled;
        $this->notificationTypes = $preferences->custom_settings;
    }

    public function loadRegionalSettings()
    {
        $settings = $this->property->regionalSettings;
        if ($settings) {
            $this->timezone = $settings->timezone;
            $this->dateFormat = $settings->date_format;
            $this->timeFormat = $settings->time_format;
            $this->currency = $settings->currency;
            $this->language = $settings->language;
            $this->numberFormat = $settings->number_format;
            $this->firstDayOfWeek = $settings->first_day_of_week;
        }
    }

    public function savePaymentSettings()
    {
        $this->validate([
            'paymentGateway' => 'required|string',
            'lateFeePercentage' => 'required|numeric|min:0|max:100',
            'gracePeriodDays' => 'required|integer|min:0',
            'reminderDaysBefore' => 'required|integer|min:1',
        ]);

        $this->property->paymentSettings()->updateOrCreate(
            ['property_id' => $this->property->id],
            [
                'payment_gateway' => $this->paymentGateway,
                'gateway_credentials' => $this->gatewayCredentials,
                'late_fee_percentage' => $this->lateFeePercentage,
                'grace_period_days' => $this->gracePeriodDays,
                'auto_charge_late_fees' => $this->autoChargeLateFees,
                'send_payment_reminders' => $this->sendPaymentReminders,
                'reminder_days_before' => $this->reminderDaysBefore,
            ]
        );

        $this->dispatch('settings-saved', ['message' => 'Payment settings saved successfully.']);
    }

    public function addCustomField()
    {
        $this->validate([
            'newFieldName' => 'required|string|max:255',
            'newFieldType' => 'required|string|in:text,number,date,select,checkbox',
            'newFieldOptions' => 'required_if:newFieldType,select|nullable|string',
        ]);

        PropertyCustomField::create([
            'name' => $this->newFieldName,
            'type' => $this->newFieldType,
            'options' => $this->newFieldType === 'select' ? explode(',', $this->newFieldOptions) : null,
            'is_required' => $this->newFieldRequired,
        ]);

        $this->reset(['newFieldName', 'newFieldType', 'newFieldOptions', 'newFieldRequired']);
        $this->dispatch('settings-saved', ['message' => 'Custom field added successfully.']);
    }

    public function saveNotificationPreferences()
    {
        $preferences = Auth::user()->notificationPreferences()->updateOrCreate(
            [
                'notification_type' => 'property_' . $this->property->id
            ],
            [
                'email_enabled' => $this->emailEnabled,
                'sms_enabled' => $this->smsEnabled,
                'push_enabled' => $this->pushEnabled,
                'custom_settings' => $this->notificationTypes,
            ]
        );

        $this->dispatch('settings-saved', ['message' => 'Notification preferences saved successfully.']);
    }

    public function saveRegionalSettings()
    {
        $this->validate([
            'timezone' => 'required|string',
            'dateFormat' => 'required|string',
            'timeFormat' => 'required|string',
            'currency' => 'required|string',
            'language' => 'required|string',
        ]);

        $this->property->regionalSettings()->updateOrCreate(
            ['property_id' => $this->property->id],
            [
                'timezone' => $this->timezone,
                'date_format' => $this->dateFormat,
                'time_format' => $this->timeFormat,
                'currency' => $this->currency,
                'language' => $this->language,
                'number_format' => $this->numberFormat,
                'first_day_of_week' => $this->firstDayOfWeek,
            ]
        );

        $this->dispatch('settings-saved', ['message' => 'Regional settings saved successfully.']);
    }

    public function render()
    {
        return view('livewire.settings.property-settings-manager', [
            'customFields' => PropertyCustomField::where('is_active', true)->get(),
            'availableTimezones' => timezone_identifiers_list(),
            'availableCurrencies' => ['USD', 'EUR', 'GBP', 'INR', 'AUD', 'CAD'],
            'availableLanguages' => ['en' => 'English', 'es' => 'Spanish', 'fr' => 'French'],
        ]);
    }
} 