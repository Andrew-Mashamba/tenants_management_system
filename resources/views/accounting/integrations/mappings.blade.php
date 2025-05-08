<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Mappings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $integration->provider }} Account Mappings
                        </h3>
                        
                        <form action="{{ route('accounting.integrations.sync', [$property, $integration]) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Sync Accounts
                            </button>
                        </form>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Provider Account
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        System Account
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($mappings as $mapping)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $mapping->provider_account_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $mapping->provider_account_id }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('accounting.mappings.update', [$property, $integration, $mapping]) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <select name="system_account" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="this.form.submit()">
                                                    <option value="bank" {{ $mapping->system_account === 'bank' ? 'selected' : '' }}>Bank</option>
                                                    <option value="accounts_receivable" {{ $mapping->system_account === 'accounts_receivable' ? 'selected' : '' }}>Accounts Receivable</option>
                                                    <option value="current_asset" {{ $mapping->system_account === 'current_asset' ? 'selected' : '' }}>Current Asset</option>
                                                    <option value="fixed_asset" {{ $mapping->system_account === 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                                                    <option value="other_asset" {{ $mapping->system_account === 'other_asset' ? 'selected' : '' }}>Other Asset</option>
                                                    <option value="accounts_payable" {{ $mapping->system_account === 'accounts_payable' ? 'selected' : '' }}>Accounts Payable</option>
                                                    <option value="credit_card" {{ $mapping->system_account === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                                    <option value="current_liability" {{ $mapping->system_account === 'current_liability' ? 'selected' : '' }}>Current Liability</option>
                                                    <option value="long_term_liability" {{ $mapping->system_account === 'long_term_liability' ? 'selected' : '' }}>Long Term Liability</option>
                                                    <option value="equity" {{ $mapping->system_account === 'equity' ? 'selected' : '' }}>Equity</option>
                                                    <option value="income" {{ $mapping->system_account === 'income' ? 'selected' : '' }}>Income</option>
                                                    <option value="cost_of_goods_sold" {{ $mapping->system_account === 'cost_of_goods_sold' ? 'selected' : '' }}>Cost of Goods Sold</option>
                                                    <option value="expense" {{ $mapping->system_account === 'expense' ? 'selected' : '' }}>Expense</option>
                                                    <option value="other_income" {{ $mapping->system_account === 'other_income' ? 'selected' : '' }}>Other Income</option>
                                                    <option value="other_expense" {{ $mapping->system_account === 'other_expense' ? 'selected' : '' }}>Other Expense</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form action="{{ route('accounting.mappings.destroy', [$property, $integration, $mapping]) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this mapping?')">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No account mappings found. Please sync accounts first.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 