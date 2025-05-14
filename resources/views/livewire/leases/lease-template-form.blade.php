<div>
    <div class="flex flex-row justify-between gap-6 p-6">
        <!-- Template List Section -->
        <div class="w-1/2 bg-white rounded-lg shadow-sm">

        <!-- back button next to new template button -->
            <div class="p-6">
                <div class="flex flex-row justify-between items-center mb-6">
                <!-- <div class="flex justify-between items-center mb-6"> -->
                    <h1 class="text-2xl font-bold text-gray-900">Lease Templates</h1>
                    <button wire:click="resetForm" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Template
                    </button>
                    <a href="{{ route('leases.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Back
                    </a>
                </div>
                <!-- </div> -->
                <div class="space-y-2">
                    @forelse ($templates as $templateItem)
                        <div class="group">
                            <a href="" class="block" wire:click.prevent="selectTemplate({{ $templateItem->id }})">
                                <div class="p-4 rounded-lg border {{ $template && $template->id === $templateItem->id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-lg font-medium text-gray-900">{{ $templateItem->name }}</h3>
                                                @if($templateItem->is_default)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Default
                                                    </span>
                                                @endif
                                                @if(!$templateItem->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">{{ $templateItem->description }}</p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No templates</h3>  
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Template Form Section -->
        <div class="w-1/2">
            @if(session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <form wire:submit.prevent="save" class="space-y-6">
                        <!-- Template Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Template Name</label>
                            <input wire:model="name" type="text" id="name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" id="description" rows="3"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Template Content</label>
                            <textarea wire:model="content" id="content" rows="10"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @error('content') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Use variables like <code>@{{tenant_name}}</code>, <code>@{{property_name}}</code>, <code>@{{unit_number}}</code>, etc.
                            </p>
                        </div>

                        <!-- Checkboxes -->
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <input wire:model="is_active" type="checkbox" id="is_active"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active Template</label>
                            </div>

                            <div class="flex items-center">
                                <input wire:model="is_default" type="checkbox" id="is_default"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="is_default" class="ml-2 block text-sm text-gray-900">Set as Default</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ $template ? 'Update Template' : 'Create Template' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    
</div>
