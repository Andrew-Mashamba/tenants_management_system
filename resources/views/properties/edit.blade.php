@extends('layouts.app')

@section('title', 'Edit Property')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Edit Property</h1>
                <a href="{{ route('properties.index') }}"
                    class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200">
                    Back to Properties
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
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
                                            <label for="name" class="block text-sm font-medium text-gray-700">Property
                                                Name</label>
                                            <input type="text" wire:model="name" id="name"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6">
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea wire:model="description" id="description" rows="3"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6">
                                            <label for="address"
                                                class="block text-sm font-medium text-gray-700">Address</label>
                                            <input type="text" wire:model="address" id="address"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                            <input type="text" wire:model="city" id="city"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('city') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                            <input type="text" wire:model="state" id="state"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('state') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="country"
                                                class="block text-sm font-medium text-gray-700">Country</label>
                                            <input type="text" wire:model="country" id="country"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('country') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal
                                                Code</label>
                                            <input type="text" wire:model="postal_code" id="postal_code"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
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
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="landlord_id"
                                                class="block text-sm font-medium text-gray-700">Landlord</label>
                                            <select wire:model="landlord_id" id="landlord_id"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select Landlord</option>
                                                @foreach($landlords as $landlord)
                                                    <option value="{{ $landlord->id }}">{{ $landlord->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('landlord_id') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="agent_id"
                                                class="block text-sm font-medium text-gray-700">Agent</label>
                                            <select wire:model="agent_id" id="agent_id"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select Agent</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('agent_id') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{--<div class="col-span-6 sm:col-span-3">
                                            <label for="status"
                                                class="block text-sm font-medium text-gray-700">Status</label>
                                            <select wire:model="status" id="status"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>--}}

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="property_type"
                                                class="block text-sm font-medium text-gray-700">Property Type</label>
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

                                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                                        <div>
                                                            <label for="new_unit_type"
                                                                class="block text-sm font-medium text-gray-700">Unit
                                                                Type</label>
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
                                                                class="block text-sm font-medium text-gray-700">Number of
                                                                Units</label>
                                                            <input type="number" wire:model="new_unit_amount"
                                                                id="new_unit_amount" min="1"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                            @error('new_unit_amount') <span
                                                            class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                        </div>

                                                        <div>
                                                            <label for="new_unit_description"
                                                                class="block text-sm font-medium text-gray-700">Unit
                                                                Name/Description</label>
                                                            <input type="text" wire:model="new_unit_description"
                                                                id="new_unit_description" placeholder="e.g., 2 Bedroom, 2 Bath"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                            @error('new_unit_description') <span
                                                            class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                        </div>

                                                        <div>
                                                            <label for="new_unit_price"
                                                                class="block text-sm font-medium text-gray-700">Unit Price Per
                                                                Month</label>
                                                            <input type="number" wire:model="new_unit_price" id="new_unit_price"
                                                                placeholder="e.g., 1000"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                            @error('new_unit_price') <span
                                                            class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                        </div>

                                                        <div class="col-span-4">
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit
                                                                Amenities</label>
                                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                                @foreach($availableAmenities as $amenity)
                                                                    <div class="flex items-center">
                                                                        <input type="checkbox" wire:model="new_unit_amenities"
                                                                            value="{{ $amenity }}"
                                                                            id="new_unit_amenity_{{ $amenity }}"
                                                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                        <label for="new_unit_amenity_{{ $amenity }}"
                                                                            class="ml-2 block text-sm text-gray-900">
                                                                            {{ ucwords(str_replace('_', ' ', $amenity)) }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <div class="flex items-end">
                                                            <button type="button" wire:click="addUnit"
                                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                Add Unit
                                                            </button>
                                                        </div>
                                                    </div>

                                                    @if(count($unit_list) > 0)
                                                        <div class="mt-4">
                                                            <h5 class="text-sm font-medium text-gray-700 mb-2">Added Units</h5>
                                                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                                                <ul class="divide-y divide-gray-200">
                                                                    @foreach($unit_list as $index => $unit)
                                                                        <li class="px-4 py-3">
                                                                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-medium text-gray-700">Unit
                                                                                        Type</label>
                                                                                    <select
                                                                                        wire:model.live="unit_list.{{ $index }}.type"
                                                                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                                                                                                <option value="industrial">Industrial
                                                                                                </option>
                                                                                                <option value="hotel">Hotel</option>
                                                                                                <option value="other">Other</option>
                                                                                            </optgroup>
                                                                                        @endif
                                                                                    </select>
                                                                                </div>

                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-medium text-gray-700">Status</label>
                                                                                    <select
                                                                                        wire:model.live="unit_list.{{ $index }}.status"
                                                                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                                        <option value="available">Available</option>
                                                                                        <option value="occupied">Occupied</option>
                                                                                        <option value="maintenance">Maintenance</option>
                                                                                    </select>
                                                                                </div>

                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-medium text-gray-700">Unit
                                                                                        Name/Description</label>
                                                                                    <input type="text"
                                                                                        wire:model.live="unit_list.{{ $index }}.description"
                                                                                        placeholder="e.g., 2 Bedroom, 2 Bath"
                                                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                                </div>

                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-medium text-gray-700">Unit
                                                                                        Price Per Month</label>
                                                                                    <input type="number"
                                                                                        wire:model.live="unit_list.{{ $index }}.unit_price"
                                                                                        placeholder="e.g., 1000"
                                                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                                </div>

                                                                                <div class="flex items-end">
                                                                                    <button type="button"
                                                                                        wire:click="removeUnit({{ $index }})"
                                                                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                                        Remove
                                                                                    </button>
                                                                                </div>

                                                                                <div class="col-span-5">
                                                                                    <label
                                                                                        class="block text-sm font-medium text-gray-700 mb-2">Unit
                                                                                        Amenities</label>
                                                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                                                        @foreach($availableAmenities as $amenity)
                                                                                            <div class="flex items-center">
                                                                                                <input type="checkbox"
                                                                                                    wire:model.live="unit_list.{{ $index }}.amenities"
                                                                                                    value="{{ $amenity }}"
                                                                                                    id="unit_{{ $index }}_amenity_{{ $amenity }}"
                                                                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                                                <label
                                                                                                    for="unit_{{ $index }}_amenity_{{ $amenity }}"
                                                                                                    class="ml-2 block text-sm text-gray-900">
                                                                                                    {{ ucwords(str_replace('_', ' ', $amenity)) }}
                                                                                                </label>
                                                                                            </div>
                                                                                        @endforeach
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
                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="total_units" class="block text-sm font-medium text-gray-700">Total
                                                Units</label>
                                            <input disabled type="number" wire:model="total_units" id="total_units"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('total_units') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- calculate available units from status(active units) and amount of units -->
                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="available_units"
                                                class="block text-sm font-medium text-gray-700">Available Units</label>
                                            <input disabled type="number" wire:model="available_units" id="available_units"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('available_units') <span
                                            class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        {{--<div class="col-span-6 sm:col-span-2">
                                            <label for="year_built" class="block text-sm font-medium text-gray-700">Year
                                                Built</label>
                                            <input type="number" wire:model="year_built" id="year_built"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('year_built') <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
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
                                        Upload images of the property.
                                    </p>
                                </div>
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <div class="grid grid-cols-2 gap-4">
                                        @if($images)
                                            @foreach($images as $index => $image)
                                                <div class="relative">
                                                    <img src="{{ asset('storage/' . $image) }}" alt="Property image"
                                                        class="w-full h-48 object-cover rounded-lg">
                                                    <button type="button" wire:click="removeImage({{ $index }})"
                                                        class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <input type="file" wire:model="newImages" multiple
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        @error('newImages.*') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" wire:click="cancel"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save
                            </button>
                        </div>
                    </form>
                </div>



            </div>
        </div>
    </div>
@endsection