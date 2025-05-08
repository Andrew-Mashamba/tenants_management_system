<div class="space-y-6">
    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="$set('activeTab', 'payment')" class="@if($activeTab === 'payment') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Payment Settings
            </button>
            <button wire:click="$set('activeTab', 'custom-fields')" class="@if($activeTab === 'custom-fields') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Custom Fields
            </button>
            <button wire:click="$set('activeTab', 'notifications')" class="@if($activeTab === 'notifications') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Notification Preferences
            </button>
            <button wire:click="$set('activeTab', 'regional')" class="@if($activeTab === 'regional') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Regional Settings
            </button>
        </nav>
    </div>

    <!-- Payment Settings -->
    <div x-show="$wire.activeTab === 'payment'" class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Payment Gateway Configuration</h3>
            <form wire:submit="savePaymentSettings" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="paymentGateway" class="block text-sm font-medium text-gray-700">Payment Gateway</label>
                        <select wire:model="paymentGateway" id="paymentGateway" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Gateway</option>
                            <option value="stripe">Stripe</option>
                            <option value="paypal">PayPal</option>
                            <option value="mpesa">M-Pesa</option>
                        </select>
                        @error('paymentGateway') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="lateFeePercentage" class="block text-sm font-medium text-gray-700">Late Fee Percentage</label>
                        <input type="number" wire:model="lateFeePercentage" id="lateFeePercentage" step="0.01" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('lateFeePercentage') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="gracePeriodDays" class="block text-sm font-medium text-gray-700">Grace Period (Days)</label>
                        <input type="number" wire:model="gracePeriodDays" id="gracePeriodDays" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('gracePeriodDays') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="reminderDaysBefore" class="block text-sm font-medium text-gray-700">Payment Reminder Days</label>
                        <input type="number" wire:model="reminderDaysBefore" id="reminderDaysBefore" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('reminderDaysBefore') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="autoChargeLateFees" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Auto-charge Late Fees</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" wire:model="sendPaymentReminders" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Send Payment Reminders</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Payment Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Fields -->
    <div x-show="$wire.activeTab === 'custom-fields'" class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Custom Fields</h3>
            
            <!-- Add New Field Form -->
            <form wire:submit="addCustomField" class="mb-8 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="newFieldName" class="block text-sm font-medium text-gray-700">Field Name</label>
                        <input type="text" wire:model="newFieldName" id="newFieldName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('newFieldName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="newFieldType" class="block text-sm font-medium text-gray-700">Field Type</label>
                        <select wire:model="newFieldType" id="newFieldType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Type</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="select">Select</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                        @error('newFieldType') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($newFieldType === 'select')
                        <div class="sm:col-span-2">
                            <label for="newFieldOptions" class="block text-sm font-medium text-gray-700">Options (comma-separated)</label>
                            <input type="text" wire:model="newFieldOptions" id="newFieldOptions" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('newFieldOptions') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="sm:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="newFieldRequired" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Required Field</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Custom Field
                    </button>
                </div>
            </form>

            <!-- Existing Fields List -->
            <div class="mt-8">
                <h4 class="text-base font-medium text-gray-900 mb-4">Existing Custom Fields</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($customFields as $field)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($field->type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $field->is_required ? 'Yes' : 'No' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="editField({{ $field->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No custom fields found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Preferences -->
    <div x-show="$wire.activeTab === 'notifications'" class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Notification Preferences</h3>
            <form wire:submit="saveNotificationPreferences" class="space-y-6">
                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="emailEnabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Email Notifications</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" wire:model="smsEnabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">SMS Notifications</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" wire:model="pushEnabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Push Notifications</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Notification Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Regional Settings -->
    <div x-show="$wire.activeTab === 'regional'" class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Regional Settings</h3>
            <form wire:submit="saveRegionalSettings" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                        <select wire:model="timezone" id="timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Timezone</option>
                            @foreach($availableTimezones as $tz)
                                <option value="{{ $tz }}">{{ $tz }}</option>
                            @endforeach
                        </select>
                        @error('timezone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="dateFormat" class="block text-sm font-medium text-gray-700">Date Format</label>
                        <select wire:model="dateFormat" id="dateFormat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Y-m-d">YYYY-MM-DD</option>
                            <option value="d/m/Y">DD/MM/YYYY</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                        </select>
                        @error('dateFormat') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="timeFormat" class="block text-sm font-medium text-gray-700">Time Format</label>
                        <select wire:model="timeFormat" id="timeFormat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="H:i">24-hour (14:30)</option>
                            <option value="h:i A">12-hour (02:30 PM)</option>
                        </select>
                        @error('timeFormat') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                        <select wire:model="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($availableCurrencies as $curr)
                                <option value="{{ $curr }}">{{ $curr }}</option>
                            @endforeach
                        </select>
                        @error('currency') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                        <select wire:model="language" id="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($availableLanguages as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('language') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="firstDayOfWeek" class="block text-sm font-medium text-gray-700">First Day of Week</label>
                        <select wire:model="firstDayOfWeek" id="firstDayOfWeek" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="sunday">Sunday</option>
                            <option value="monday">Monday</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Regional Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    <div
        x-data="{ show: false, message: '' }"
        x-on:settings-saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed bottom-4 right-4 bg-green-50 border-l-4 border-green-400 p-4"
        style="display: none;"
    >
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700" x-text="message"></p>
            </div>
        </div>
    </div>
</div> 