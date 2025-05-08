<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Document') }} - {{ $property->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('properties.documents.store', $property) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-label for="title" :value="__('Title')" />
                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="category" :value="__('Category')" />
                            <x-select id="category" name="category" class="block mt-1 w-full" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                                    </option>
                                @endforeach
                            </x-select>
                            @error('category')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="unit_id" :value="__('Unit (Optional)')" />
                            <x-select id="unit_id" name="unit_id" class="block mt-1 w-full">
                                <option value="">Property-wide document</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            @error('unit_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="file" :value="__('Document File')" />
                            <x-input id="file" class="block mt-1 w-full" type="file" name="file" required />
                            <p class="mt-1 text-sm text-gray-500">Maximum file size: 10MB</p>
                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="description" :value="__('Description (Optional)')" />
                            <x-textarea id="description" name="description" class="block mt-1 w-full" rows="3">{{ old('description') }}</x-textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="expiry_date" :value="__('Expiry Date (Optional)')" />
                            <x-input id="expiry_date" class="block mt-1 w-full" type="date" name="expiry_date" :value="old('expiry_date')" />
                            @error('expiry_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <x-checkbox id="is_public" name="is_public" :checked="old('is_public', false)" />
                            <x-label for="is_public" class="ml-2" :value="__('Make this document public')" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('properties.documents.index', $property) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                            <x-button class="ml-4">
                                {{ __('Upload Document') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
