<div>
    @if($showModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Transfer Inventory
                        </h3>
                        <div class="mt-2">
                            <div class="mb-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" wire:model="quantity" id="quantity" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('quantity') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="toOrganizationId" class="block text-sm font-medium text-gray-700">To Organization</label>
                                <select wire:model="toOrganizationId" id="toOrganizationId" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select an organization</option>
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                                @error('toOrganizationId') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4" x-data="{ open:false, search: '', users: @entangle('users'), selectedUser: @entangle('toUserId') }">
                                <label for="toUserId" class="block text-sm font-medium text-gray-700">To User</label>
                                <div class="relative mt-1">
                                    <input
                                        type="text"
                                        x-model="search"
                                        @input="$wire.searchUsers(search); open = true"
                                        placeholder="Search for a user..."
                                        class="block w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                    <div x-show="search.length > 0 && open" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                                        <ul class="py-1 overflow-auto text-base leading-6 rounded-md max-h-60 focus:outline-none sm:text-sm sm:leading-5">
                                            <template x-for="user in users" :key="user.id">
                                                <li @click="selectedUser = user.id; search = user.name; open = false" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-blue-600 hover:text-white">
                                                    <span x-text="user.name" class="block truncate"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                @error('toUserId') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="transferInventory" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Transfer
                        </button>
                        <button wire:click="$set('showModal', false)" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
