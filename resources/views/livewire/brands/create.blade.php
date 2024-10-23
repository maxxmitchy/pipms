<form wire:submit.prevent="save">
    <div class="mb-4">
        <label for="brand_name" class="block text-sm font-medium text-left text-gray-700">Brand Name</label>
        <input type="text" id="brand_name" wire:model.defer="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>
    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 text-white bg-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Create Brand
        </button>
    </div>
</form>
