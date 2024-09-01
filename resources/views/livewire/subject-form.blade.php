<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Название') }}</label>
            <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="name" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="total_hours" class="block text-sm font-medium text-gray-700">{{ __('Всего часов') }}</label>
            <input type="number" wire:model="total_hours" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="total_hours" required>
            @error('total_hours') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="semester_id" class="block text-sm font-medium text-gray-700">{{ __('Семестр') }}</label>
            <select wire:model="semester_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="semester_id" required>
                <option value="">{{ __('Выберите семестр') }}</option>
                @foreach(\App\Models\Semester::all() as $semester)
                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                @endforeach
            </select>
            @error('semester_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600">
            {{ $subjectId ? __('Обновить') : __('Создать') }}
        </button>
    </form>
</div>