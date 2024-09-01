<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif
    <div class="flex items-center mb-4">
        <label for="semesterFilter" class="mr-2 text-sm font-medium text-gray-700">{{ __('Фильтр по семестру') }}</label>
        <select wire:model.live="semester" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" wire:model="semester">
            <option value="all">{{ __('Все семестры') }}</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
            @endforeach
        </select>
    </div>


    <div class="overflow-x-auto">
        @if($this->subjects->isEmpty())
            <p class="text-gray-500 text-center py-4">{{ __('Таблица пуста') }}</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Название') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Всего часов') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Семестр') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Действия') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $subject->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $subject->total_hours }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $subject->semester->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="text-yellow-500 hover:underline">{{ __('Редактировать') }}</a>
                                <button wire:click="delete({{ $subject->id }})" class="text-red-500 hover:underline ml-2">{{ __('Удалить') }}</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">
        {{ $this->subjects->onEachSide(1)->links() }}
    </div>
</div>
