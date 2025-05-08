<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Two Factor Authentication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (!auth()->user()->hasTwoFactorEnabled())
                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Two factor authentication is not enabled yet.') }}
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600">
                                {{ __('Scan this QR code with your authenticator app:') }}
                            </p>
                            <div class="mt-2">
                                {!! QrCode::size(200)->generate($qrCode) !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600">
                                {{ __('Or enter this code manually:') }}
                            </p>
                            <div class="mt-2 font-mono text-sm">
                                {{ $secret }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('two-factor.enable') }}">
                            @csrf

                            <div>
                                <x-label for="code" :value="__('Authentication Code')" />
                                <x-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button>
                                    {{ __('Enable') }}
                                </x-button>
                            </div>
                        </form>
                    @else
                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Two factor authentication is enabled.') }}
                        </div>

                        <form method="POST" action="{{ route('two-factor.disable') }}">
                            @csrf

                            <div>
                                <x-label for="code" :value="__('Authentication Code')" />
                                <x-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button class="bg-red-600 hover:bg-red-700">
                                    {{ __('Disable') }}
                                </x-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
