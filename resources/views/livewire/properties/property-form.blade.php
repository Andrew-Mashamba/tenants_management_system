<div>
    @if($property?->id)
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-gray-100">
            
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Property</h1>
                    <a href="{{ route('properties.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to List
                            </a>
                </div>        
            
        </div>
    @endif
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <form wire:submit="save" class="space-y-6">
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Property Information</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Enter the basic information about the property.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Property Name</label>
                                <input type="text" wire:model="name" id="name"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="description" id="description" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" wire:model="address" id="address"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" wire:model="city" id="city"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" wire:model="state" id="state"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" wire:model="country" id="country"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal
                                    Code</label>
                                <input type="text" wire:model="postal_code" id="postal_code"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="">
                    <div class="md:col-span-1 mb-2">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Property Details</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Enter the specific details about the property.
                        </p>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-2">
                                <label for="landlord_id"
                                    class="block text-sm font-medium text-gray-700">Landlord</label>
                                <select wire:model="landlord_id" id="landlord_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Landlord</option>
                                    @foreach($landlords as $landlord)
                                        <option value="{{ $landlord->id }}">{{ $landlord->name }}</option>
                                    @endforeach
                                </select>
                                @error('landlord_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent</label>
                                <select wire:model="agent_id" id="agent_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Agent</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                @error('agent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{--<div class="col-span-6 sm:col-span-3">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model="status" id="status"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>--}}

                            <div class="col-span-6 sm:col-span-2">
                                <label for="property_type" class="block text-sm font-medium text-gray-700">Property
                                    Type</label>
                                <select wire:model.live="property_type" id="property_type"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Property Type</option>
                                    <option value="residential">Residential</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="mixed">Mixed (Residential and Commercial)</option>
                                </select>
                                @error('property_type') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($property_type)
                                <div class="col-span-6">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="text-lg font-medium text-gray-900 mb-4">Add Units</h4>

                                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                                            <div>
                                                <label for="new_unit_type"
                                                    class="block text-sm font-medium text-gray-700">Unit Type</label>
                                                <select wire:model="new_unit_type" id="new_unit_type"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="">Select Type</option>
                                                    @if($property_type == 'residential' || $property_type == 'mixed')
                                                        <optgroup label="Residential">
                                                            <option value="apartment">Apartment</option>
                                                            <option value="house">House</option>
                                                            <option value="condo">Condo</option>
                                                            <option value="townhouse">Townhouse</option>
                                                            <option value="villa">Villa</option>
                                                            <option value="land">Land</option>
                                                        </optgroup>
                                                    @endif
                                                    @if($property_type == 'commercial' || $property_type == 'mixed')
                                                        <optgroup label="Commercial">
                                                            <option value="office">Office</option>
                                                            <option value="retail">Retail</option>
                                                            <option value="industrial">Industrial</option>
                                                            <option value="hotel">Hotel</option>
                                                            <option value="other">Other</option>
                                                        </optgroup>
                                                    @endif
                                                </select>
                                                @error('new_unit_type') <span
                                                class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="new_unit_status"
                                                    class="block text-sm font-medium text-gray-700">Status</label>
                                                <select wire:model="new_unit_status" id="new_unit_status"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="">Select Status</option>
                                                    <option value="available">Available</option>
                                                    <option value="occupied">Occupied</option>
                                                    <option value="maintenance">Maintenance</option>
                                                </select>
                                                @error('new_unit_status') <span
                                                class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="new_unit_amount"
                                                    class="block text-sm font-medium text-gray-700">Number of Units</label>
                                                <input type="number" wire:model="new_unit_amount" id="new_unit_amount"
                                                    min="1"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                @error('new_unit_amount') <span
                                                class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="new_unit_description"
                                                    class="block text-sm font-medium text-gray-700">Unit Name</label>
                                                <input type="text" wire:model="new_unit_description"
                                                    id="new_unit_description" placeholder="e.g., 2 Bedroom, 2 Bath"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                @error('new_unit_description') <span
                                                class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="new_unit_price"
                                                    class="block text-sm font-medium text-gray-700">Unit Price Per Month</label>
                                                <input type="number" wire:model="new_unit_price" id="new_unit_price"
                                                    placeholder="e.g., 1000"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                @error('new_unit_price') <span
                                                class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="flex items-end">
                                                <button type="button" wire:click="addUnit"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Add Unit
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Amenities</label>
                                            <div x-data="{ unitType: @entangle('new_unit_type') }">
                                                {{-- Residential Amenities --}}
                                                <div x-show="['apartment', 'house', 'condo', 'townhouse', 'villa'].includes(unitType)">
                                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Residential Amenities</h5>
                                                    <div class="grid grid-cols-4 gap-2">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="air_conditioning" id="new_unit_amenity_air_conditioning"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_air_conditioning" class="ml-2 block text-sm text-gray-900">Air Conditioning</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="heating" id="new_unit_amenity_heating"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_heating" class="ml-2 block text-sm text-gray-900">Heating</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="balcony" id="new_unit_amenity_balcony"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_balcony" class="ml-2 block text-sm text-gray-900">Balcony</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="parking" id="new_unit_amenity_parking"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_parking" class="ml-2 block text-sm text-gray-900">Parking</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="furnished" id="new_unit_amenity_furnished"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_furnished" class="ml-2 block text-sm text-gray-900">Furnished</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="pets_allowed" id="new_unit_amenity_pets_allowed"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_pets_allowed" class="ml-2 block text-sm text-gray-900">Pets Allowed</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="garden" id="new_unit_amenity_garden"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_garden" class="ml-2 block text-sm text-gray-900">Garden</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="pool" id="new_unit_amenity_pool"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_pool" class="ml-2 block text-sm text-gray-900">Pool</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Commercial Amenities --}}
                                                <div x-show="['office', 'retail', 'industrial'].includes(unitType)">
                                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Commercial Amenities</h5>
                                                    <div class="grid grid-cols-4 gap-2">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="security_system" id="new_unit_amenity_security_system"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_security_system" class="ml-2 block text-sm text-gray-900">Security System</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="elevator" id="new_unit_amenity_elevator"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_elevator" class="ml-2 block text-sm text-gray-900">Elevator</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="loading_dock" id="new_unit_amenity_loading_dock"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_loading_dock" class="ml-2 block text-sm text-gray-900">Loading Dock</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="parking_lot" id="new_unit_amenity_parking_lot"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_parking_lot" class="ml-2 block text-sm text-gray-900">Parking Lot</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="high_ceiling" id="new_unit_amenity_high_ceiling"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_high_ceiling" class="ml-2 block text-sm text-gray-900">High Ceiling</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="warehouse_space" id="new_unit_amenity_warehouse_space"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_warehouse_space" class="ml-2 block text-sm text-gray-900">Warehouse Space</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="meeting_rooms" id="new_unit_amenity_meeting_rooms"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_meeting_rooms" class="ml-2 block text-sm text-gray-900">Meeting Rooms</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="reception_area" id="new_unit_amenity_reception_area"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_reception_area" class="ml-2 block text-sm text-gray-900">Reception Area</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Hotel Specific Amenities --}}
                                                <div x-show="unitType === 'hotel'">
                                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Hotel Amenities</h5>
                                                    <div class="grid grid-cols-4 gap-2">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="room_service" id="new_unit_amenity_room_service"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_room_service" class="ml-2 block text-sm text-gray-900">Room Service</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="restaurant" id="new_unit_amenity_restaurant"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_restaurant" class="ml-2 block text-sm text-gray-900">Restaurant</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="spa" id="new_unit_amenity_spa"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_spa" class="ml-2 block text-sm text-gray-900">Spa</label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" wire:model="new_unit_amenities" value="gym" id="new_unit_amenity_gym"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                            <label for="new_unit_amenity_gym" class="ml-2 block text-sm text-gray-900">Gym</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(count($unit_list) > 0)
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Added Units ({{ count($unit_list) }})</h5>
                                                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                                    <ul class="divide-y divide-gray-200">
                                                        @foreach($unit_list as $index => $unit)
                                                            <li class="px-4 py-3">
                                                                <div x-data="{ open: false }">
                                                                    <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                                                        <div class="flex items-center">
                                                                            <span class="text-sm font-medium text-gray-900 mr-2">Unit #{{ $index + 1 }}</span>
                                                                            <span class="text-sm text-gray-500">{{ $unit['description'] ?? 'No description' }}</span>
                                                                        </div>
                                                                        <div class="flex items-center">
                                                                            <span class="text-sm text-gray-500 mr-2">{{ ucfirst($unit['unit_type'] ?? '') }} - {{ ucfirst($unit['status'] ?? '') }}</span>
                                                                            <svg class="h-5 w-5 text-gray-400" :class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                    <div x-show="open" x-transition class="mt-4">
                                                                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700">Unit Type</label>
                                                                                <select wire:model.live="unit_list.{{ $index }}.unit_type"
                                                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                                    @if($property_type == 'residential' || $property_type == 'mixed')
                                                                                        <optgroup label="Residential">a
                                                                                            <option value="apartment">Apartment</option>
                                                                                            <option value="house">House</option>
                                                                                            <option value="condo">Condo</option>
                                                                                            <option value="townhouse">Townhouse</option>
                                                                                            <option value="villa">Villa</option>
                                                                                            <option value="land">Land</option>
                                                                                        </optgroup>
                                                                                    @endif
                                                                                    @if($property_type == 'commercial' || $property_type == 'mixed')
                                                                                        <optgroup label="Commercial">
                                                                                            <option value="office">Office</option>
                                                                                            <option value="retail">Retail</option>
                                                                                            <option value="industrial">Industrial</option>
                                                                                            <option value="hotel">Hotel</option>
                                                                                            <option value="other">Other</option>
                                                                                        </optgroup>
                                                                                    @endif
                                                                                </select>
                                                                            </div>

                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                                                                <select wire:model.live="unit_list.{{ $index }}.status"
                                                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                                    <option value="available">Available</option>
                                                                                    <option value="occupied">Occupied</option>
                                                                                    <option value="maintenance">Maintenance</option>
                                                                                </select>
                                                                            </div>

                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700">Unit Name</label>
                                                                                <input type="text" wire:model.live="unit_list.{{ $index }}.description"
                                                                                    placeholder="e.g., 2 Bedroom, 2 Bath"
                                                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                            </div>

                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700">Unit Price Per Month</label>
                                                                                <input type="number" wire:model.live="unit_list.{{ $index }}.unit_price"
                                                                                    placeholder="e.g., 1000"
                                                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                            </div>

                                                                            <div class="flex items-end">
                                                                                <button type="button" wire:click="removeUnit({{ $index }})"
                                                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                                    Remove
                                                                                </button>
                                                                            </div>

                                                                            <div class="col-span-5">
                                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Amenities</label>
                                                                                <div x-data="{ unitType: $wire.get('unit_list.{{ $index }}.unit_type') }" x-init="$watch('unitType', value => $wire.set('unit_list.{{ $index }}.unit_type', value))">
                                                                                    {{-- Residential Amenities --}}
                                                                                    <div x-show="['apartment', 'house', 'condo', 'townhouse', 'villa'].includes(unitType)">
                                                                                        <h6 class="text-sm font-medium text-gray-600 mb-2">Residential Amenities</h6>
                                                                                        <div class="grid grid-cols-4 gap-2">
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="air_conditioning"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Air Conditioning</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="heating"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Heating</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="balcony"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Balcony</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="parking"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Parking</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="furnished"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Furnished</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="pets_allowed"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Pets Allowed</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="garden"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Garden</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="pool"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Pool</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    {{-- Commercial Amenities --}}
                                                                                    <div x-show="['office', 'retail', 'industrial'].includes(unitType)">
                                                                                        <h6 class="text-sm font-medium text-gray-600 mb-2">Commercial Amenities</h6>
                                                                                        <div class="grid grid-cols-4 gap-2">
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="security_system"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Security System</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="elevator"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Elevator</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="loading_dock"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Loading Dock</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="parking_lot"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Parking Lot</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="high_ceiling"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">High Ceiling</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="warehouse_space"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Warehouse Space</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="meeting_rooms"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Meeting Rooms</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="reception_area"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Reception Area</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    {{-- Hotel Specific Amenities --}}
                                                                                    <div x-show="unitType === 'hotel'">
                                                                                        <h6 class="text-sm font-medium text-gray-600 mb-2">Hotel Amenities</h6>
                                                                                        <div class="grid grid-cols-4 gap-2">
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="room_service"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Room Service</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="restaurant"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Restaurant</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="spa"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Spa</label>
                                                                                            </div>
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox" wire:model.live="unit_list.{{ $index }}.amenities" value="gym"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label class="ml-2 block text-sm text-gray-900">Gym</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- sum all the units from the property_subtype -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="total_units" class="block text-sm font-medium text-gray-700">Total
                                    Units</label>
                                <input disabled type="number" wire:model="total_units" id="total_units"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('total_units') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- calculate available units from status(active units) and amount of units -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="available_units" class="block text-sm font-medium text-gray-700">Available
                                    Units</label>
                                <input disabled type="number" wire:model="available_units" id="available_units"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('available_units') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            {{--<div class="col-span-6 sm:col-span-2">
                                <label for="year_built" class="block text-sm font-medium text-gray-700">Year
                                    Built</label>
                                <input type="number" wire:model="year_built" id="year_built"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('year_built') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Amenities</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Select the amenities available in the property.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($availableAmenities as $amenity)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="amenities" value="{{ $amenity }}"
                                        id="amenity_{{ $amenity }}"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="amenity_{{ $amenity }}" class="ml-2 block text-sm text-gray-900">
                                        Shared {{ ucwords(str_replace('_', ' ', $amenity)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Images</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Upload images of the property. You can select multiple images at once.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="space-y-6">
                            {{-- Image Upload Area --}}
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                                x-data="{ 
                                    isUploading: false,
                                    progress: 0,
                                    handleFiles(e) {
                                        this.isUploading = true;
                                        let files = e.target.files;
                                        for (let i = 0; i < files.length; i++) {
                                            let reader = new FileReader();
                                            reader.onload = (e) => {
                                                $wire.upload('newImages', files[i], (uploadedFilename) => {
                                                    // Success
                                                }, () => {
                                                    // Error
                                                }, (event) => {
                                                    this.progress = event.detail.progress;
                                                });
                                            };
                                            reader.readAsDataURL(files[i]);
                                        }
                                    }
                                }"
                                x-on:dragover.prevent="$el.classList.add('border-indigo-500')"
                                x-on:dragleave.prevent="$el.classList.remove('border-indigo-500')"
                                x-on:drop.prevent="handleFiles($event.dataTransfer)">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload files</span>
                                            <input id="file-upload" type="file" class="sr-only" multiple wire:model="newImages" x-on:change="handleFiles($event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                                {{-- Upload Progress --}}
                                <div x-show="isUploading" class="mt-4">
                                    <div class="relative pt-1">
                                        <div class="overflow-hidden h-2 text-xs flex rounded bg-indigo-200">
                                            <div x-bind:style="'width: ' + progress + '%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Image Preview Grid --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @if(is_array($images) && count($images) > 0)
                                    @foreach($images as $index => $image)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Property image"
                                                class="w-full h-48 object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                                <button type="button" wire:click="removeImage({{ $index }})"
                                                    class="text-white hover:text-red-500 focus:outline-none">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- Preview of newly uploaded images --}}
                                @if($newImages && is_array($newImages))
                                    @foreach($newImages as $index => $image)
                                        <div class="relative group">
                                            <img src="{{ $image->temporaryUrl() }}" alt="New property image"
                                                class="w-full h-48 object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                                <button type="button" wire:click="removeNewImage({{ $index }})"
                                                    class="text-white hover:text-red-500 focus:outline-none">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @error('newImages.*') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center px-4 py-5 sm:px-6">
                <div>
                   
                </div>
                <div class="flex space-x-3">                    
                    <button type="button" wire:click="cancel"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $property?->id ? 'Update Property' : 'Save Property' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>