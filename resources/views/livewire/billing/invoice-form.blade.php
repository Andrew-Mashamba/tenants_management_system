<div>
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Invoice Information</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Enter the invoice details and add items.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="lease_id" class="block text-sm font-medium text-gray-700">Lease</label>
                            <select wire:model.live="lease_id" id="lease_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Lease</option>
                                @foreach($leases as $lease)
                                    <option value="{{ $lease->id }}">
                                        {{ $lease->property->name }} - 
                                        @foreach($lease->unit_ids as $unitId)
                                            @php
                                                $unit = $units->firstWhere('id', $unitId);
                                            @endphp
                                            @if($unit)
                                                Unit {{ $unit->unit_id }}{{ !$loop->last ? ', ' : '' }}
                                            @endif
                                        @endforeach
                                        ({{ $lease->tenant->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('lease_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                            <input type="text" wire:model="invoice_number" id="invoice_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" readonly>
                            @error('invoice_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                            <input type="date" wire:model="issue_date" id="issue_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('issue_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" wire:model="due_date" id="due_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Cancelled</option>
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

        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Invoice Items</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Add items to the invoice.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="space-y-4">
                        @foreach($items as $index => $item)
                            <div class="border border-gray-200 rounded-md p-4">
                                <div class="grid grid-cols-6 gap-4">
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <input type="text" wire:model="items.{{ $index }}.description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error("items.{$index}.description") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Type</label>
                                        <select wire:model="items.{{ $index }}.type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="rent">Rent</option>
                                            <option value="utility">Utility</option>
                                            <option value="maintenance">Maintenance</option>
                                            <option value="deposit">Deposit</option>
                                            <option value="fee">Fee</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error("items.{$index}.type") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                        <input type="number" step="0.01" wire:model="items.{{ $index }}.quantity" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error("items.{$index}.quantity") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                        <input type="number" step="0.01" @if($item['type'] == 'rent') disabled @endif wire:model="items.{{ $index }}.unit_price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error("items.{$index}.unit_price") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                                        <input type="number" step="0.01" wire:model="items.{{ $index }}.tax_rate" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error("items.{$index}.tax_rate") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">&nbsp;</label>
                                        <button type="button" wire:click="removeItem({{ $index }})" class="mt-1 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-end">
                            <button type="button" wire:click="addItem" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add Item
                            </button>
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
