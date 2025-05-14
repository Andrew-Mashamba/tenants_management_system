@extends('layouts.app')

@section('title', 'Compose Message')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('communication.messages.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Recipient -->
                        <div>
                            <x-label for="recipient_id" value="{{ __('Recipient') }}" />
                            <select id="recipient_id" name="recipient_id" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a recipient') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="recipient_id" class="mt-2" />
                        </div>

                        <!-- Subject -->
                        <div>
                            <x-label for="subject" value="{{ __('Subject') }}" />
                            <x-input id="subject" name="subject" type="text" class="mt-1 block w-full" value="{{ old('subject') }}" required />
                            <x-input-error for="subject" class="mt-2" />
                        </div>

                        <!-- Content -->
                        <div>
                            <x-label for="content" value="{{ __('Message') }}" />
                            <textarea id="content" name="content" rows="6" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>{{ old('content') }}</textarea>
                            <x-input-error for="content" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('communication.messages.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-button>
                                {{ __('Send Message') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 