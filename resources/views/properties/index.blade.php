@extends('layouts.app')

@section('title', 'Properties')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Properties
                </h3>
                <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Property
                </a>
            </div>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                List of all properties in the system.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <livewire:properties.property-list />
        </div>
    </div>
@endsection 