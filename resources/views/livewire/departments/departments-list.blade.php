<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        @if($departments->isEmpty())
            <p class="text-gray-500 text-center py-4">{{ __('Таблица пуста') }}</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Название') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Группы') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Действия') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $department->name }}</td>
                            
                            <!-- Колонка с выпадающим списком для групп -->
                            <td class="py-2 px-4 border-b border-gray-200">
                                <!-- Выпадающий список с группами -->
                                <select class="block w-full border-gray-300 rounded-md shadow-sm">
                                    @forelse($department->groups as $group)
                                        <option value="{{ $group->id }}">
                                            {{ $group->name }}
                                        </option>
                                    @empty
                                        <option>{{ __('Группы не привязаны') }}</option>
                                    @endforelse
                                </select>
                            </td>

                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('departments.edit', $department->id) }}" class="text-yellow-500 hover:underline">{{ __('Редактировать') }}</a>
                                <button wire:click="delete({{ $department->id }})" class="text-red-500 hover:underline ml-2">{{ __('Удалить') }}</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">
        {{ $departments->links() }}
    </div>
</div>
