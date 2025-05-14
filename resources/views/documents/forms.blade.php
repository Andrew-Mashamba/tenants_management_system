@extends('layouts.app')

@section('title', 'Forms & Templates')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700">Search Forms</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Search forms and templates...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="all">All Categories</option>
                                <option value="tenant">Tenant Forms</option>
                                <option value="maintenance">Maintenance Forms</option>
                                <option value="inspection">Inspection Forms</option>
                                <option value="financial">Financial Forms</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms Grid -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Tenant Application Form -->
                        <div class="bg-white rounded-lg border p-6 hover:shadow-lg transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-900">Tenant Application Form</h3>
                                </div>
                            </div>
                            <p class="text-gray-500 mb-4">Standard application form for new tenant applications.</p>
                            <div class="flex space-x-3">
                                <button class="text-indigo-600 hover:text-indigo-900">View</button>
                                <button class="text-indigo-600 hover:text-indigo-900">Download</button>
                                <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </div>
                        </div>

                        <!-- Maintenance Request Form -->
                        <div class="bg-white rounded-lg border p-6 hover:shadow-lg transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-100 rounded-md p-2">
                                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-900">Maintenance Request Form</h3>
                                </div>
                            </div>
                            <p class="text-gray-500 mb-4">Form for tenants to submit maintenance requests.</p>
                            <div class="flex space-x-3">
                                <button class="text-indigo-600 hover:text-indigo-900">View</button>
                                <button class="text-indigo-600 hover:text-indigo-900">Download</button>
                                <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </div>
                        </div>

                        <!-- Property Inspection Form -->
                        <div class="bg-white rounded-lg border p-6 hover:shadow-lg transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2">
                                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-900">Property Inspection Form</h3>
                                </div>
                            </div>
                            <p class="text-gray-500 mb-4">Comprehensive property inspection checklist.</p>
                            <div class="flex space-x-3">
                                <button class="text-indigo-600 hover:text-indigo-900">View</button>
                                <button class="text-indigo-600 hover:text-indigo-900">Download</button>
                                <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </div>
                        </div>

                        <!-- Add more form cards as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
