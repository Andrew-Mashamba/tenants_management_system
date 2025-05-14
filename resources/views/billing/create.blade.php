@extends('layouts.app')

@section('title', 'Billing')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Billing
                </h3>               
            </div>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Create invoice for a tenant.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <livewire:billing.invoice-form />
        </div>
    </div>
@endsection
