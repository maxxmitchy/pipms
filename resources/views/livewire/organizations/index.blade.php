<!-- resources/views/livewire/organizations/index.blade.php -->
<div class="pb-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Organizations
                    </h2>
                    <a href="{{ route('organizations.create') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        New Organization
                    </a>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1 pr-4">
                            <div class="relative">
                                <input wire:model.debounce.300ms="search" type="text" class="w-full py-2 pl-10 pr-4 font-medium text-gray-600 rounded-lg shadow focus:outline-none focus:shadow-outline" placeholder="Search organizations...">
                                <div class="absolute top-0 left-0 inline-flex items-center p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                        <circle cx="10" cy="10" r="7" />
                                        <line x1="21" y1="21" x2="15" y2="15" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
                        <table class="relative w-full whitespace-no-wrap bg-white border-collapse table-auto table-striped">
                            <thead>
                                <tr class="text-left">
                                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Name</th>
                                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Address</th>
                                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Phone</th>
                                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Email</th>
                                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($organizations as $organization)
                                    <tr>
                                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $organization->name }}</td>
                                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $organization->address }}</td>
                                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $organization->phone }}</td>
                                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $organization->email }}</td>
                                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                                            @can('update', $organization)
                                                <a href="{{ route('organizations.edit', $organization) }}" class="mr-2 text-blue-600 hover:text-blue-900">Edit</a>
                                            @endcan
                                            <button wire:click="delete({{ $organization->id }})" class="mr-2 text-red-600 hover:text-red-900">Delete</button>
                                            <a href="{{ route('inventory.index', ['organization' => $organization]) }}" class="text-green-600 hover:text-green-900">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                    </svg>
                                                    Inventory
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 border-t border-gray-200 border-dashed">No organizations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $organizations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
