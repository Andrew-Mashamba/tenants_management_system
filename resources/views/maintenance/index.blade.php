@extends('layouts.app')

@section('title', 'Maintenance Requests')

@section('content')
<div class="p-12 bg-slate-100">
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-white border rounded-xl">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">
                    Maintenance Requests
                </h3>
                <a href="{{ route('maintenance.requests.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Maintenance Request
                </a>
            </div>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                List of all maintenance requests in the system.
            </p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <livewire:maintenance.maintenance-request-list />
        </div>
    </div>
</div>

@endsection 