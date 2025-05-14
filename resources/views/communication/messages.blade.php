@extends('layouts.app')

@section('title', 'Messages')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Message Filters -->
                    <div class="mb-6 flex space-x-4">
                        <button class="px-4 py-2 text-sm font-medium text-indigo-600 border-b-2 border-indigo-600">
                            All Messages
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Unread
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Sent
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Archived
                        </button>
                    </div>

                    <!-- Messages List -->
                    <div class="space-y-4">
                        @livewire('communication.message-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
