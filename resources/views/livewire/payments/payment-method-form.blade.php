<div>
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Payment Method</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Add or update a payment method for making payments.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                            <select wire:model="type" id="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Type</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs">{{ $errors->first('type') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="provider" class="block text-sm font-medium text-gray-700">Provider</label>
                            <select wire:model="provider" id="provider" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Provider</option>
                                @if($type === 'card')
                                    <option value="stripe">Stripe</option>
                                    <option value="paypal">PayPal</option>
                                @elseif($type === 'bank_transfer')
                                    <option value="standard_bank">Standard Bank</option>
                                    <option value="absa">ABSA</option>
                                    <option value="fnb">First National Bank</option>
                                @elseif($type === 'mobile_money')
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="mtn">MTN Mobile Money</option>
                                    <option value="airtel">Airtel Money</option>
                                @endif
                            </select>
                            @error('provider') <span class="text-red-500 text-xs">{{ $errors->first('provider') }}</span> @enderror
                        </div>

                        @if($type === 'bank_transfer')
                            <div class="col-span-6 sm:col-span-3">
                                <label for="account_name" class="block text-sm font-medium text-gray-700">Account Name</label>
                                <input type="text" wire:model="account_name" id="account_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('account_name') <span class="text-red-500 text-xs">{{ $errors->first('account_name') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                <input type="text" wire:model="account_number" id="account_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('account_number') <span class="text-red-500 text-xs">{{ $errors->first('account_number') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                                <input type="text" wire:model="bank_name" id="bank_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('bank_name') <span class="text-red-500 text-xs">{{ $errors->first('bank_name') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="bank_code" class="block text-sm font-medium text-gray-700">Bank Code</label>
                                <input type="text" wire:model="bank_code" id="bank_code" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('bank_code') <span class="text-red-500 text-xs">{{ $errors->first('bank_code') }}</span> @enderror
                            </div>
                        @endif

                        @if($type === 'card')
                            <div class="col-span-6">
                                <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                <input type="text" wire:model="card_number" id="card_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('card_number') <span class="text-red-500 text-xs">{{ $errors->first('card_number') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="card_expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="text" wire:model="card_expiry" id="card_expiry" placeholder="MM/YY" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('card_expiry') <span class="text-red-500 text-xs">{{ $errors->first('card_expiry') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="card_cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" wire:model="card_cvv" id="card_cvv" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('card_cvv') <span class="text-red-500 text-xs">{{ $errors->first('card_cvv') }}</span> @enderror
                            </div>
                        @endif

                        @if($type === 'mobile_money')
                            <div class="col-span-6 sm:col-span-3">
                                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                                <input type="text" wire:model="mobile_number" id="mobile_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('mobile_number') <span class="text-red-500 text-xs">{{ $errors->first('mobile_number') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="mobile_network" class="block text-sm font-medium text-gray-700">Network</label>
                                <select wire:model="mobile_network" id="mobile_network" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Network</option>
                                    <option value="safaricom">Safaricom</option>
                                    <option value="mtn">MTN</option>
                                    <option value="airtel">Airtel</option>
                                </select>
                                @error('mobile_network') <span class="text-red-500 text-xs">{{ $errors->first('mobile_network') }}</span> @enderror
                            </div>
                        @endif

                        <div class="col-span-6">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_default" id="is_default" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_default" class="ml-2 block text-sm text-gray-900">Set as default payment method</label>
                            </div>
                            @error('is_default') <span class="text-red-500 text-xs">{{ $errors->first('is_default') }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ $paymentMethod ? 'Update Payment Method' : 'Add Payment Method' }}
            </button>
        </div>
    </form>
</div> 