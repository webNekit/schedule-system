<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        @if($semesters->isEmpty())
            <p class="text-gray-500 text-center py-4">{{ __('Таблица пуста') }}</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Название') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Дата начала') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Дата окончания') }}</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">{{ __('Действия') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semesters as $semester)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $semester->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($semester->start_date)->format('d-m-y') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($semester->end_date)->format('d-m-y') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('semesters.edit', $semester->id) }}" class="text-yellow-500 hover:underline">{{ __('Редактировать') }}</a>
                                <button wire:click="delete({{ $semester->id }})" class="text-red-500 hover:underline ml-2">{{ __('Удалить') }}</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
