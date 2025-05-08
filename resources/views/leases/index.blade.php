@extends('layouts.app')

@section('title', 'Leases')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Leases
                </h3>
                <div class="space-x-2">
                    <a href="{{ route('leases.templates.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Templates
                    </a>
                    <a href="{{ route('leases.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Lease
                    </a>
                </div>
            </div>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                List of all lease agreements in the system.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <livewire:leases.lease-list />
        </div>
    </div>
@endsection
