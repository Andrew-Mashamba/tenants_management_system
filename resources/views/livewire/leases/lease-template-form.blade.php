        <div class="flex justify-end">
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ isset($template) && $template ? 'Update Template' : 'Create Template' }}
            </button>
        </div>

                            <textarea wire:model="content" id="content" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            <p class="mt-2 text-sm text-gray-500">
                                Use variables like @{{tenant_name}}, @{{property_name}}, @{{unit_number}}, etc. in the template.
                            </p>