<div class="max-w-2xl mx-auto">
    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Inventory Item</h2>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <label for="medication_id" class="block text-sm font-medium text-gray-700">Medication</label>
                    <select id="medication_id" wire:model="medication_id" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Select a medication</option>
                        @foreach($medications as $medication)
                            <option value="{{ $medication->id }}">{{ $medication->name }}</option>
                        @endforeach
                    </select>
                    @error('medication_id') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="quantity" wire:model="quantity" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('quantity') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                    <input type="date" id="expiration_date" wire:model="expiration_date" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('expiration_date') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="batch_number" class="block text-sm font-medium text-gray-700">Batch Number</label>
                    <input type="text" id="batch_number" wire:model="batch_number" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('batch_number') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Level</label>
                    <input type="number" id="reorder_level" wire:model="reorder_level" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('reorder_level') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="organization_id" class="block text-sm font-medium text-gray-700">Organization</label>
                    <select id="organization_id" wire:model="organization_id" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Select an organization</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                        @endforeach
                    </select>
                    @error('organization_id') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Inventory Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
