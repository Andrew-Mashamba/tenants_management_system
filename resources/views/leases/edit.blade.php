@extends('layouts.app')

@section('title', 'Edit Lease')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Lease</h1>
            <a href="{{ route('leases.index') }}" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200">
                Back to Leases
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <livewire:leases.lease-form :lease="$lease" />
        </div>
    </div>
</div>
@endsection 