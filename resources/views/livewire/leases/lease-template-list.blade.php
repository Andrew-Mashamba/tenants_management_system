<div>
    <div class="flex justify-between items-center mb-4">
        <div class="flex-1 max-w-sm">
            <input type="text" wire:model.live="search" placeholder="Search templates..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
        <a href="{{ route('lease-templates.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Create Template
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse ($templates as $template)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    {{ $template->name }}
                                </p>
                                @if($template->is_default)
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Default
                                    </span>
                                @endif
                                @if(!$template->is_active)
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
                                <a href="{{ route('lease-templates.edit', $template) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                                    Edit
                                </a>
                                <button wire:click="delete({{ $template->id }})" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    {{ Str::limit($template->description, 100) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 sm:px-6">
                    <p class="text-gray-500 text-center">No templates found.</p>
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        {{ $templates->links() }}
    </div>
</div> 