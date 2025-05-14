<div>
    <div class="p-6">
        {{-- Header Section --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Property Details</h2>
                <p class="text-sm text-gray-600 mt-1">Property ID: #{{ $property->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('properties.edit', $property) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Property
                </a>
                <a href="{{ route('properties.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Basic Information --}}
            <div class="lg:col-span-2">
                {{-- Property Images Gallery --}}
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                        <h3 class="text-lg leading-6 font-medium text-white">
                            Property Images
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 p-4">
                        @if($property->images && count($property->images) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($property->images as $image)
                                    <div class="relative group" data-lightbox="property-images">
                                        <!-- open image in new tab -->
                                        <a href="{{ Storage::url($image) }}" target="_blank">
                                            <img src="{{ Storage::url($image) }}" 
                                                 alt="Property Image"
                                                 class="w-full h-48 object-cover rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                                        </a>
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg"></div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No images available</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Property Details Card --}}
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                        <h3 class="text-lg leading-6 font-medium text-white">
                            {{ $property->property_type }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-indigo-400">
                            Property Information
                        </p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            {{-- Basic Details Section --}}
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $property->address }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Property Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ ucfirst($property->property_type) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $property->status === 'available' ? 'bg-green-100 text-green-800' : 
                                           ($property->status === 'rented' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </dd>
                            </div>

                            {{-- Property Specifications --}}                            
                            {{-- <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Bedrooms</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $property->bedrooms }}
                                </dd>
                            </div> 
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Bathrooms</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $property->bathrooms }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Square Feet</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ number_format($property->square_feet) }} sq ft
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Year Built</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $property->year_built }}
                                </dd>
                            </div>--}}

                            {{-- Additional Details --}}
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $property->description }}
                                </dd>
                            </div>
                            {{-- <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Features</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($property->features ?? [] as $feature)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                </dd>
                            </div> --}}
                        </dl>
                    </div>
                </div>

                {{-- Units Section --}}
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
                    <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                        <h3 class="text-lg leading-6 font-medium text-white">
                            Property Units
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        @if($property->units && count($property->units) > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($property->units as $unit)
                                    <div x-data="{ open: false }" class="hover:bg-gray-50 transition-colors duration-200">
                                        {{-- Unit Header (Always Visible) --}}
                                        <div class="p-4 cursor-pointer" @click="open = !open">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                                                            <span class="text-lg font-medium leading-none text-indigo-600">{{ $unit->unit_number }}</span>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-lg font-medium text-gray-900">{{ $unit->type }}</h4>
                                                        {{-- <p class="text-sm text-gray-500">{{ number_format($unit->size) }} sq ft</p> --}}
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <div class="text-right">
                                                        <p class="text-sm font-medium text-gray-900">${{ number_format($unit->unit_price, 2) }}</p>
                                                        <p class="text-xs text-gray-500">per month</p>
                                                    </div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $unit->status === 'available' ? 'bg-green-100 text-green-800' : 
                                                           ($unit->status === 'occupied' ? 'bg-red-100 text-red-800' : 
                                                           ($unit->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 
                                                           ($unit->status === 'reserved' ? 'bg-gray-100 text-gray-800' : 
                                                           'bg-gray-100 text-gray-800' )))}}">
                                                        {{ ucfirst($unit->status) }}
                                                    </span>
                                                    <svg class="h-5 w-5 text-gray-400 transform transition-transform duration-200" 
                                                         :class="{ 'rotate-180': open }"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Unit Details (Expandable) --}}
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                                             x-transition:enter-end="opacity-100 transform translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 transform translate-y-0"
                                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                                             class="border-t border-gray-200 bg-gray-50">
                                            <div class="p-4 space-y-4">
                                                {{-- Basic Information --}}
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                    {{-- <div>
                                                        <p class="text-sm font-medium text-gray-500">Floor</p>
                                                        <p class="mt-1 text-sm text-gray-900">{{ $unit->floor }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Size</p>
                                                        <p class="mt-1 text-sm text-gray-900">{{ number_format($unit->size) }} sq ft</p>
                                                    </div> --}}
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Unit Price</p>
                                                        <p class="mt-1 text-sm text-gray-900">${{ number_format($unit->unit_price, 2) }}</p>
                                                    </div>
                                                    {{-- <div>
                                                        <p class="text-sm font-medium text-gray-500">Deposit</p>
                                                        <p class="mt-1 text-sm text-gray-900">${{ number_format($unit->deposit, 2) }}</p>
                                                    </div> --}}
                                                </div>

                                                {{-- Description --}}
                                                @if($unit->description)
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Description</p>
                                                        <p class="mt-1 text-sm text-gray-900">{{ $unit->description }}</p>
                                                    </div>
                                                @endif

                                                {{-- Features --}}
                                                @if($unit->features && count($unit->features) > 0)
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Features</p>
                                                        <div class="mt-2 flex flex-wrap gap-2">
                                                            @foreach($unit->features as $feature)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ $feature }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Additional Details --}}
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                    <div>
                                                         @if($unit->availability_status == 'occupied' || $unit->availability_status == 'maintenance' || $unit->availability_status == 'reserved')
                                                            <p class="text-sm font-medium text-gray-500">Available From</p>                                                       
                                                            <p class="text-sm font-medium text-gray-900">{{ $unit->availability_from->format('M d, Y') }}</p>
                                                        @else
                                                            <p class="text-sm font-medium text-gray-500">Available</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Lease Term</p>
                                                        <p class="mt-1 text-sm text-gray-900">{{ $unit->lease_term ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                                                        <p class="mt-1 text-sm text-gray-900">{{ $unit->updated_at->format('M d, Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Created At</p>
                                                        <p class="mt-1 text-sm text-gray-900">{{ $unit->created_at->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No units available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column - Additional Information --}}
            <div class="lg:col-span-1">
                {{-- Property Status Card --}}
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Property Status
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Listed Date</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Last Updated</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Owner Information Card --}}
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Owner Information
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Owner Name</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->owner_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Contact Number</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->owner_phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->owner_email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location Information Card --}}
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Location Details
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">City</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->city ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">State</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->state ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">ZIP Code</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $property->zip_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>