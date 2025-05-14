@extends('layouts.app')

@section('title', 'Notifications')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Notifications') }}
                        </h2>
                        <div class="flex space-x-4">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Mark All as Read
                            </button>
                            <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Clear All
                            </button>
                        </div>
                    </div>

                    <!-- Notification Filters -->
                    <div class="mb-6 flex space-x-4">
                        <button class="px-4 py-2 text-sm font-medium text-indigo-600 border-b-2 border-indigo-600">
                            All Notifications
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Unread
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            System
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Alerts
                        </button>
                    </div>

                    <!-- Notifications List -->
                    <div class="space-y-4">
                        <!-- Sample Notification Items -->
                        <div class="flex items-start p-4 bg-white border rounded-lg shadow-sm">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">New Maintenance Request</p>
                                    <p class="text-sm text-gray-500">2 hours ago</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">A new maintenance request has been submitted for Unit 101.</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-white border rounded-lg shadow-sm">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">Payment Received</p>
                                    <p class="text-sm text-gray-500">5 hours ago</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Payment of $1,200 has been received for Unit 203.</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-white border rounded-lg shadow-sm">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">Lease Expiring Soon</p>
                                    <p class="text-sm text-gray-500">1 day ago</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">The lease for Unit 305 will expire in 30 days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
