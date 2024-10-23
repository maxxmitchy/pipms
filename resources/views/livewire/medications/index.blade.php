<div class="min-h-screen py-8 bg-gray-100 dark:bg-gray-900">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
            <div class="px-6 py-4 bg-gray-600 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-white">Medications</h2>
            </div>

            <div class="p-6">
                @if (session()->has('message'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded" role="alert">
                        <p class="font-bold">Success!</p>
                        <p>{{ session('message') }}</p>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded" role="alert">
                        <p class="font-bold">Error!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="flex flex-col items-start justify-between mb-6 space-y-4 sm:flex-row sm:space-y-0">
                    <div class="flex items-center space-x-8">
                        @can('create', App\Models\Medication::class)
                            <a href="{{ route('medications.create') }}" class="px-6 py-3 text-white transition duration-150 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    New
                                </span>
                            </a>
                        @endcan

                        <div class="flex space-x-2">
                            <span class="text-gray-700 dark:text-gray-300">View:</span>
                            <button wire:click="setView('table')" class="px-3 py-1 text-sm {{ $view === 'table' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Table
                            </button>
                            <button wire:click="setView('card')" class="px-3 py-1 text-sm {{ $view === 'card' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Cards
                            </button>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-64">
                        <input
                            wire:model.live="search"
                            type="text"
                            placeholder="Search medications..."
                            class="w-full px-4 py-2 pl-10 pr-4 text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-500"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                @if($view === 'card')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($medications as $medication)
                            <div class="overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-md dark:bg-gray-700 hover:shadow-lg">
                                <div class="p-6">
                                    <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">{{ $medication->name }}</h3>
                                    <p class="mb-4 text-gray-600 dark:text-gray-300">{{ Str::limit($medication->description, 100) }}</p>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Dosage</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $medication->dosage }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Manufacturer</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $medication->manufacturer }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Brand</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $medication->brand->name ?? 'Generic' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-4 space-x-2">
                                        <a href="{{ route('medications.edit', $medication) }}" class="px-4 py-2 text-blue-600 transition-colors duration-300 bg-blue-100 rounded hover:bg-blue-200">Edit</a>
                                        @can('delete', $medication)
                                            <button wire:click="confirmDelete({{ $medication->id }})" class="px-4 py-2 text-red-600 transition-colors duration-300 bg-red-100 rounded hover:bg-red-200">Delete</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600" wire:click="sortBy('name')">
                                        Name
                                        @if ($sortField === 'name')
                                            <span class="ml-1">
                                                @if ($sortDirection === 'asc')
                                                    &#9650;
                                                @else
                                                    &#9660;
                                                @endif
                                            </span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600" wire:click="sortBy('dosage')">
                                        Dosage
                                        @if ($sortField === 'dosage')
                                            <span class="ml-1">
                                                @if ($sortDirection === 'asc')
                                                    &#9650;
                                                @else
                                                    &#9660;
                                                @endif
                                            </span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600" wire:click="sortBy('manufacturer')">
                                        Manufacturer
                                        @if ($sortField === 'manufacturer')
                                            <span class="ml-1">
                                                @if ($sortDirection === 'asc')
                                                    &#9650;
                                                @else
                                                    &#9660;
                                                @endif
                                            </span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600" wire:click="sortBy('brand_id')">
                                        Brand
                                        @if ($sortField === 'brand_id')
                                            <span class="ml-1">
                                                @if ($sortDirection === 'asc')
                                                    &#9650;
                                                @else
                                                    &#9660;
                                                @endif
                                            </span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach ($medications as $medication)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">
                                            {{ $medication->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                            {{ $medication->dosage }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                            {{ $medication->manufacturer }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                            {{ $medication->brand->name ?? 'Generic' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <a href="{{ route('medications.edit', $medication) }}" class="mr-3 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                                            @can('delete', $medication)
                                                <button wire:click="confirmDelete({{ $medication->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-6">
                    {{ $medications->links() }}
                </div>

                <!-- Delete Confirmation Modal -->
                @if($medicationToDelete)
                <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                            Delete Medication
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Are you sure you want to delete this medication? This action cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button wire:click="delete" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Delete
                                </button>
                                <button wire:click="cancelDelete" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 dark:hover:bg-gray-500">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
