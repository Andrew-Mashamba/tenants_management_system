<div>
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Vendor Information</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Add or update vendor information and services.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('name') <span class="text-red-500 text-xs">{{ $errors->first('name') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model="email" id="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('email') <span class="text-red-500 text-xs">{{ $errors->first('email') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" wire:model="phone" id="phone" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('phone') <span class="text-red-500 text-xs">{{ $errors->first('phone') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" wire:model="company_name" id="company_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('company_name') <span class="text-red-500 text-xs">{{ $errors->first('company_name') }}</span> @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" wire:model="address" id="address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('address') <span class="text-red-500 text-xs">{{ $errors->first('address') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" wire:model="city" id="city" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('city') <span class="text-red-500 text-xs">{{ $errors->first('city') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                            <input type="text" wire:model="state" id="state" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('state') <span class="text-red-500 text-xs">{{ $errors->first('state') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" wire:model="postal_code" id="postal_code" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('postal_code') <span class="text-red-500 text-xs">{{ $errors->first('postal_code') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                            <input type="text" wire:model="specialization" id="specialization" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('specialization') <span class="text-red-500 text-xs">{{ $errors->first('specialization') }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                            <input type="number" wire:model="hourly_rate" id="hourly_rate" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('hourly_rate') <span class="text-red-500 text-xs">{{ $errors->first('hourly_rate') }}</span> @enderror
                        </div>

                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Service Areas</label>
                            <div class="mt-1 space-y-2">
                                @foreach($service_areas as $index => $area)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" wire:model="service_areas.{{ $index }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <button type="button" wire:click="removeServiceArea({{ $index }})" class="text-red-600 hover:text-red-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="addServiceArea" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add Service Area
                                </button>
                            </div>
                            @error('service_areas') <span class="text-red-500 text-xs">{{ $errors->first('service_areas') }}</span> @enderror
                        </div>

                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Certifications</label>
                            <div class="mt-1 space-y-2">
                                @foreach($certifications as $index => $certification)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" wire:model="certifications.{{ $index }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <button type="button" wire:click="removeCertification({{ $index }})" class="text-red-600 hover:text-red-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="addCertification" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add Certification
                                </button>
                            </div>
                            @error('certifications') <span class="text-red-500 text-xs">{{ $errors->first('certifications') }}</span> @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea wire:model="notes" id="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $errors->first('notes') }}</span> @enderror
                        </div>

                        <div class="col-span-6">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_active" id="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                            @error('is_active') <span class="text-red-500 text-xs">{{ $errors->first('is_active') }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($vendor)
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Services</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Add services provided by this vendor.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="new_service_name" class="block text-sm font-medium text-gray-700">Service Name</label>
                                <input type="text" wire:model="new_service_name" id="new_service_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('new_service_name') <span class="text-red-500 text-xs">{{ $errors->first('new_service_name') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="new_service_price" class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" wire:model="new_service_price" id="new_service_price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('new_service_price') <span class="text-red-500 text-xs">{{ $errors->first('new_service_price') }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="new_service_unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                <select wire:model="new_service_unit" id="new_service_unit" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Unit</option>
                                    <option value="hour">Per Hour</option>
                                    <option value="day">Per Day</option>
                                    <option value="fixed">Fixed Price</option>
                                </select>
                                @error('new_service_unit') <span class="text-red-500 text-xs">{{ $errors->first('new_service_unit') }}</span> @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="new_service_description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="new_service_description" id="new_service_description" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('new_service_description') <span class="text-red-500 text-xs">{{ $errors->first('new_service_description') }}</span> @enderror
                            </div>

                            <div class="col-span-6">
                                <button type="button" wire:click="addService" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add Service
                                </button>
                            </div>

                            @if($vendor->services->count() > 0)
                                <div class="col-span-6">
                                    <h4 class="text-sm font-medium text-gray-900">Existing Services</h4>
                                    <div class="mt-2 space-y-2">
                                        @foreach($vendor->services as $service)
                                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $service->service_name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $service->formatted_price }}</p>
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $service->description }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end">
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ $vendor ? 'Update Vendor' : 'Create Vendor' }}
            </button>
        </div>
    </form>
</div> 