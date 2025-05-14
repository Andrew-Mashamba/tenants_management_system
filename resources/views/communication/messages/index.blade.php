@extends('layouts.app')

@section('title', 'Messages')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex space-x-4">
                        <a href="{{ route('communication.messages.index') }}" class="px-4 py-2 text-sm font-medium {{ request('filter') === null ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                            All Messages
                        </a>
                        <a href="{{ route('communication.messages.index', ['filter' => 'unread']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') === 'unread' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Unread
                        </a>
                        <a href="{{ route('communication.messages.index', ['filter' => 'sent']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') === 'sent' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Sent
                        </a>
                        <a href="{{ route('communication.messages.index', ['filter' => 'archived']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') === 'archived' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Archived
                        </a>
                    </div>
                </div>

                <!-- Messages List -->
                <div class="divide-y divide-gray-200">
                    @forelse($messages as $message)
                        <div class="p-6 hover:bg-gray-50 {{ $message->read_at ? '' : 'bg-gray-50' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 font-medium">
                                                {{ substr($message->sender->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('communication.messages.show', $message) }}" class="text-sm font-medium text-gray-900">
                                            {{ $message->subject }}
                                        </a>
                                        <p class="text-sm text-gray-500">
                                            {{ Str::limit($message->content, 100) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-500">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                    <div class="flex space-x-2">
                                        @if(!$message->read_at && $message->recipient_id === auth()->id())
                                            <form action="{{ route('communication.messages.read', $message) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Mark as read</span>
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        @if(!$message->archived_at)
                                            <form action="{{ route('communication.messages.archive', $message) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Archive</span>
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('communication.messages.destroy', $message) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500">
                                                <span class="sr-only">Delete</span>
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            No messages found.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection 