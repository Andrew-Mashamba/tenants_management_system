<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Co-tenants Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Lease Information</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Property: {{ $lease->unit->property->name }}<br>
                            Unit: {{ $lease->unit->unit_number }}<br>
                            Primary Tenant: {{ $lease->tenant->name }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Current Co-tenants</h3>
                        <div class="mt-4">
                            @forelse($lease->tenant->coTenants as $coTenant)
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $coTenant->name }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ ucfirst($coTenant->relationship) }} |
                                                {{ $coTenant->email }} |
                                                {{ $coTenant->phone }}
                                            </p>
                                        </div>
                                        <div>
                                            <button type="button" class="text-sm text-indigo-600 hover:text-indigo-900" onclick="Livewire.emit('editCoTenant', {{ $coTenant->id }})">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No co-tenants added yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Add New Co-tenant</h3>
                        <div class="mt-4">
                            <livewire:tenants.co-tenant-form :lease="$lease" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
