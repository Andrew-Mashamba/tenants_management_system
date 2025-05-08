<div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Search and Filter Section -->
        <div class="px-4 py-5 sm:px-6">
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <input type="text" 
                           wire:model.live="search" 
                           placeholder="Search activities..." 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex space-x-4">
                    <div>
                        <input type="date" 
                               wire:model.live="dateFrom" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <input type="date" 
                               wire:model.live="dateTo" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities List -->
        <div class="border-t border-gray-200">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse ($activities as $activity)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $activity->description }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $activity->created_at->format('M d, Y H:i:s') }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    IP: {{ $activity->ip_address }} | 
                                    Browser: {{ Str::limit($activity->user_agent, 50) }}
                                </p>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-sm text-gray-500">No activities found.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 sm:px-6">
            {{ $activities->links() }}
        </div>
    </div>
</div> 