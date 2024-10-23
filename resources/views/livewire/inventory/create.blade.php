<div class="max-w-2xl mx-auto overflow-hidden bg-white shadow-lg">
    <div class="px-6 py-4 bg-gray-100">
        <h2 class="text-2xl font-bold">Create Inventory Item</h2>
    </div>
    <form wire:submit.prevent="save" class="p-6 space-y-6">
        <div class="space-y-4">
            <div>
                <label for="medication_id" class="block text-sm font-medium text-gray-700">Medication</label>
                <select id="medication_id" wire:model="medication_id" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select a medication</option>
                    @foreach($medications as $medication)
                        <option value="{{ $medication->id }}">{{ $medication->name }}</option>
                    @endforeach
                </select>
                @error('medication_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <input type="number" id="quantity" wire:model="quantity" class="block w-full pr-12 border-gray-300 rounded-md sm:text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">units</span>
                    </div>
                </div>
                @error('quantity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                <input type="date" id="expiration_date" wire:model="expiration_date" class="block w-full mt-1 border-gray-300 rounded-md sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('expiration_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="batch_number" class="block text-sm font-medium text-gray-700">Batch Number</label>
                <input type="text" id="batch_number" wire:model="batch_number" class="block w-full mt-1 border-gray-300 rounded-md sm:text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. BN12345">
                @error('batch_number') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Level</label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <input type="number" id="reorder_level" wire:model="reorder_level" class="block w-full pr-12 border-gray-300 rounded-md sm:text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">units</span>
                    </div>
                </div>
                @error('reorder_level') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Quantity (quantity to request for when stock is low)</label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <input type="number" id="reorder_quantity" wire:model="reorder_quantity" class="block w-full pr-12 border-gray-300 rounded-md sm:text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">units</span>
                    </div>
                </div>
                @error('reorder_level') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="organization_id" class="block text-sm font-medium text-gray-700">Organization</label>
                <select id="organization_id" wire:model="organization_id" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select an organization</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                    @endforeach
                </select>
                @error('organization_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end">
                <a href="{{ route('organizations.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center px-4 py-2 ml-3 text-sm font-medium text-white bg-gray-700 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Inventory Item
                </button>
            </div>
        </div>
    </form>
</div>
