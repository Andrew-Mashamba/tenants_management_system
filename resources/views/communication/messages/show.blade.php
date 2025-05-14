@extends('layouts.app')

@section('title', $message->subject)
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Message Header -->
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 font-medium text-lg">
                                            {{ substr($message->sender->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $message->sender->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $message->created_at->format('F j, Y, g:i a') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                To: {{ $message->recipient->name }}
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="mt-6 prose max-w-none">
                        {{ $message->content }}
                    </div>

                    <!-- Reply Button -->
                    <div class="mt-6">
                        <a href="{{ route('communication.messages.create', ['reply_to' => $message->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Reply') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 