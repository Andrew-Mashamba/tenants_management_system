<div>
    <div class="flex justify-between items-center mb-4">
        <div class="flex-1 max-w-sm">
            <input type="text" wire:model.live="search" placeholder="Search reports..." class="w-full px-4 py-2 border rounded-md">
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Available Reports</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-base font-medium text-gray-900">Tenant Report</h4>
                        <p class="mt-1 text-sm text-gray-500">View tenant information and lease details</p>
                        <div class="mt-4">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Generate Report</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-base font-medium text-gray-900">Payment Report</h4>
                        <p class="mt-1 text-sm text-gray-500">View payment history and outstanding balances</p>
                        <div class="mt-4">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Generate Report</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-base font-medium text-gray-900">Maintenance Report</h4>
                        <p class="mt-1 text-sm text-gray-500">View maintenance requests and vendor performance</p>
                        <div class="mt-4">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Generate Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 