<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200 mt-4">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Имя</th>
                <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Нагрузка</th>
                <th class="py-2 px-4 border-b border-gray-200 text-left font-semibold text-gray-600">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $teacher->name }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">
                        <!-- Выпадающий список с дисциплинами и часами -->
                        <select class="block w-full border-gray-300 rounded-md shadow-sm">
                            @forelse($teacher->subjects_id as $subjectId)
                                @if($allSubjects->has($subjectId))
                                    @php $subject = $allSubjects->get($subjectId); @endphp
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }} - {{ $subject->total_hours }} часов
                                    </option>
                                @else
                                    <option>Дисциплина не найдена</option>
                                @endif
                            @empty
                                <option>Нет дисциплин</option>
                            @endforelse
                        </select>
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200">
                        <a href="{{ route('teachers.edit', $teacher->id) }}" class="text-yellow-500 hover:underline">Редактировать</a>
                        <button wire:click="delete({{ $teacher->id }})" class="text-red-500 hover:underline ml-2">Удалить</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $teachers->links() }}
    </div>
</div>