<div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Invoice #{{ $invoice->invoice_number }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Issued on {{ $invoice->issue_date->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($invoice->status === 'paid') bg-green-100 text-green-800
                        @elseif($invoice->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                        @elseif($invoice->status === 'draft') bg-gray-100 text-gray-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($invoice->status) }}
                    </span>
                    @if($invoice->status === 'draft')
                        <a href="{{ route('invoices.edit', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Tenant</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->tenant->name }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Property</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($invoice->lease && $invoice->lease->units && $invoice->lease->units->isNotEmpty())
                            @foreach($invoice->lease->units as $unit)
                                {{ $unit->property->name }} - Unit {{ $unit->name }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($invoice->total_amount, 2) }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Paid Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($invoice->paid_amount, 2) }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Balance</dt>
                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($invoice->balance, 2) }}</dd>
                </div>
                @if($invoice->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $invoice->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Invoice Items</h3>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Rate</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucfirst($item->type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->tax_rate }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($item->tax_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($item->total + $item->tax_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-500">
                            Subtotal:
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${{ number_format($invoice->items->sum('total'), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-500">
                            Tax:
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${{ number_format($invoice->items->sum('tax_amount'), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                            Total:
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($invoice->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
