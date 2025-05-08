<div>
    <form wire:submit="save" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Document Name</label>
            <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Document Type</label>
            <select wire:model="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Select Type</option>
                <option value="lease">Lease Agreement</option>
                <option value="id">ID Document</option>
                <option value="utility">Utility Bill</option>
                <option value="other">Other</option>
            </select>
            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="file" class="block text-sm font-medium text-gray-700">Document File</label>
            <input type="file" wire:model="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
            <input type="date" wire:model="expiry_date" id="expiry_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('expiry_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" wire:model="requires_renewal" id="requires_renewal" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="requires_renewal" class="font-medium text-gray-700">Requires Renewal</label>
                    <p class="text-gray-500">Check this if the document needs to be renewed periodically.</p>
                </div>
            </div>

            @if($requires_renewal)
                <div>
                    <label for="renewal_reminder_days" class="block text-sm font-medium text-gray-700">Reminder Days Before Expiry</label>
                    <input type="number" wire:model="renewal_reminder_days" id="renewal_reminder_days" min="1" max="365" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('renewal_reminder_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @endif
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('leases.show', $lease) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ $document ? 'Update' : 'Upload' }} Document
            </button>
        </div>
    </form>
</div> 