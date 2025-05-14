@extends('layouts.app')

@section('title', 'Upload Document')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Document Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="lease" {{ old('category') == 'lease' ? 'selected' : '' }}>Lease</option>
                                <option value="contract" {{ old('category') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="form" {{ old('category') == 'form' ? 'selected' : '' }}>Form</option>
                                <option value="invoice" {{ old('category') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">Document File</label>
                            <input type="file" id="file" name="file" required
                                class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Maximum file size: 10MB</p>
                            @error('file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="documentable_type" class="block text-sm font-medium text-gray-700">Document Type</label>
                            <select id="documentable_type" name="documentable_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="App\Models\Property" {{ old('documentable_type') == 'App\Models\Property' ? 'selected' : '' }}>Property</option>
                                <option value="App\Models\Lease" {{ old('documentable_type') == 'App\Models\Lease' ? 'selected' : '' }}>Lease</option>
                                <option value="App\Models\Tenant" {{ old('documentable_type') == 'App\Models\Tenant' ? 'selected' : '' }}>Tenant</option>
                            </select>
                            @error('documentable_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="documentable_id" class="block text-sm font-medium text-gray-700">Select Item</label>
                            <select id="documentable_id" name="documentable_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <!-- This will be populated dynamically based on documentable_type -->
                                <option value="">Select Item</option>
                            </select>
                            @error('documentable_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Upload Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('documentable_type').addEventListener('change', function() {
            const type = this.value;
            const select = document.getElementById('documentable_id');
            select.innerHTML = '<option value="">Loading...</option>';

            // Fetch items based on selected type
            fetch(`/api/${type.split('\\').pop().toLowerCase()}s`)
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '';
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name || item.title || `Item #${item.id}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    select.innerHTML = '<option value="">Error loading items</option>';
                });
        });
    </script>
    @endpush
@endsection 