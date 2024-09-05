<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        @if($workdays->isEmpty())
            <p class="text-gray-500 text-center py-4">{{ __('Таблица пуста') }}</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Дата') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Статус') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Действия') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workdays as $workday)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($workday->date)->format('d.m.y') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                @if($workday->is_active)
                                    <span class="text-green-600 font-semibold">{{ __('Активен') }}</span>
                                @else
                                    <span class="text-gray-500">{{ __('Не активен') }}</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('workdays.edit', $workday->id) }}" class="text-yellow-500 hover:underline">{{ __('Редактировать') }}</a>
                                <button wire:click="delete({{ $workday->id }})" class="text-red-500 hover:underline ml-2">{{ __('Удалить') }}</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">
        {{ $workdays->links() }}
    </div>
</div>
