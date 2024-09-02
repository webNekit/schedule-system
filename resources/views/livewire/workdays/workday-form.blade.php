<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Рабочий день</label>
            <input type="date" id="name" wire:model="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('date') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Сохранить
            </button>
        </div>
    </form>
</div>
