<div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tenant Onboarding Process</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete all steps to finalize your tenancy.</p>
        </div>

        <!-- Progress Steps -->
        <div class="px-4 py-5 sm:p-6">
            <nav aria-label="Progress">
                <ol role="list" class="border border-gray-300 rounded-md divide-y divide-gray-300 md:flex md:divide-y-0">
                    @foreach($steps as $index => $step)
                        <li class="relative md:flex-1 md:flex">
                            <div class="group flex items-center w-full">
                                <span class="px-6 py-4 flex items-center text-sm font-medium">
                                    <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $currentStep > $index ? 'bg-indigo-600' : ($currentStep === $index ? 'border-2 border-indigo-600' : 'border-2 border-gray-300') }}">
                                        <span class="{{ $currentStep > $index ? 'text-white' : ($currentStep === $index ? 'text-indigo-600' : 'text-gray-500') }}">{{ $index }}</span>
                                    </span>
                                    <span class="ml-4 text-sm font-medium {{ $currentStep > $index ? 'text-indigo-600' : ($currentStep === $index ? 'text-indigo-600' : 'text-gray-500') }}">{{ $step }}</span>
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

        <!-- Current Step Content -->
        <div class="px-4 py-5 sm:p-6">
            @if($currentStep === 1)
                <div class="space-y-6">
                    <div>
                        <label for="property_id" class="block text-sm font-medium text-gray-700">Select Property</label>
                        <select wire:model="property_id" id="property_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select a Property</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($property_id)
                        <div>
                            <label for="unit_id" class="block text-sm font-medium text-gray-700">Select Unit</label>
                            <select wire:model="unit_id" id="unit_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select a Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_number }} - {{ $unit->name }}</option>
                                @endforeach
                            </select>
                            @error('unit_id') <span class="text-red-500 text-xs">{{ $errors->first('unit_id') }}</span> @enderror
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button wire:click="startOnboarding" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Start Onboarding
                        </button>
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @if($currentStep === 2)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Application Details</h4>
                            <p class="mt-1 text-sm text-gray-500">
                                Property: {{ $properties->find($property_id)->name }}<br>
                                Unit: {{ $units->find($unit_id)->unit_number }}
                            </p>
                            <div class="mt-4">
                                <livewire:tenants.kyc-verification-form :tenant="$tenant" />
                            </div>
                        </div>
                    @elseif($currentStep === 3)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Required Documents</h4>
                            <div class="mt-4">
                                <!-- Document upload component will be added here -->
                            </div>
                        </div>
                    @elseif($currentStep === 4)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">KYC Verification Status</h4>
                            <div class="mt-4">
                                @if($tenant->kycVerification)
                                    <p class="text-sm text-gray-500">
                                        Status: {{ ucfirst($tenant->kycVerification->status) }}<br>
                                        @if($tenant->kycVerification->isVerified())
                                            Verified on: {{ $tenant->kycVerification->verified_at->format('M d, Y') }}
                                        @endif
                                    </p>
                                @else
                                    <p class="text-sm text-red-500">KYC verification not started.</p>
                                @endif
                            </div>
                        </div>
                    @elseif($currentStep === 5)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Background Check</h4>
                            <div class="mt-4">
                                <!-- Background check status and form will be added here -->
                            </div>
                        </div>
                    @elseif($currentStep === 6)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Lease Agreement</h4>
                            <div class="mt-4">
                                <!-- Lease signing component will be added here -->
                            </div>
                        </div>
                    @elseif($currentStep === 7)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Payment</h4>
                            <div class="mt-4">
                                <!-- Payment component will be added here -->
                            </div>
                        </div>
                    @endif

                    @if($workflow && !in_array($workflow->status, ['completed', 'rejected']))
                        <div class="flex justify-between">
                            <button wire:click="$emit('previousStep')" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Previous
                            </button>
                            <button wire:click="completeStep('{{ $steps[$currentStep] }}')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Complete Step
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
