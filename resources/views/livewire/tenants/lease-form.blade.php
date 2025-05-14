<div>
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Lease Information</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Enter the basic information about the lease agreement.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">

                    <!-- Property selection -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="property_id" class="block text-sm font-medium text-gray-700">Property</label>
                            <select @if($lease) disabled @endif wire:model.live="property_id" id="property_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>                      

                        <!-- Tenant selection -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700">Tenant</label>
                            <select wire:model="tenant_id" id="tenant_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                            @error('tenant_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                         <!--Units selection  -->
                         <div class="col-span-full sm:col-span-full">
                            <label class="block text-sm font-medium text-gray-700">Units</label>
                            <div class="mt-2 space-y-2">
                                @if($property_id)
                                    @if($units->count() > 0)
                                    @foreach($units as $unit)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.live="unit_ids" value="{{ $unit->id }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span>{{ $unit->name }} - {{ number_format($unit->unit_price, 2)  }} Monthly</span>
                                        </label>
                                    @endforeach
                                    @else
                                        <span class="text-gray-500">No units available</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">No property selected</span>
                                @endif
                            </div>
                            @error('unit_ids') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Start and End Date -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">ContractStart Date</label>
                            <input type="date" wire:model.live="start_date" id="start_date" min="{{ now()->toDateString() }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Contract End Date</label>
                            <input type="date" wire:model.live="end_date" id="end_date" min="{{ now()->addMonth()->toDateString() }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Total Rent Amount -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700">Total Rent Amount</label>
                            <input disabled type="number" step="0.01" wire:model="rent_amount" id="rent_amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('rent_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Billing per payment frequency -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="billing_amount" class="block text-sm font-medium text-gray-700">Billing Amount</label>
                            <input disabled type="number" step="0.01" wire:model="billing_amount" id="billing_amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('billing_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700">Deposit Amount</label>
                            <input type="number" step="0.01" wire:model="deposit_amount" id="deposit_amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('deposit_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="payment_frequency" class="block text-sm font-medium text-gray-700">Payment Frequency</label>
                            <select wire:model.live="payment_frequency" id="payment_frequency" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="annually">Annually</option>
                            </select>
                            @error('payment_frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lease Template -->
                        <div class="col-span-6 sm:col-span-3"> 
                            <label for="lease_template" class="block text-sm font-medium text-gray-700">Lease Template</label>
                            <select wire:model="lease_template" id="lease_template" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Lease Template</option>
                                @foreach($lease_templates as $lease_template)
                                    <option value="{{ $lease_template->id }}">{{ $lease_template->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="terminated">Terminated</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea wire:model="notes" id="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" wire:click="cancel" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
            </button>
        </div>
    </form>
</div>
