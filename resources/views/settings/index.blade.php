@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Settings
                </h3>
            </div>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Configure system settings and preferences.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <livewire:settings.settings-form />
        </div>
    </div>
@endsection
