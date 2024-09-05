<div class="p-4">
    <h2 class="text-2xl font-bold mb-4">Список расписаний</h2>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Рабочего дня</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Кафедры и группы</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php
                // Группировка расписаний по workday_id
                $schedulesGroupedByWorkday = $schedules->groupBy('workday_id');
            @endphp

            @foreach ($schedulesGroupedByWorkday as $workdayId => $groupedSchedules)
                @php
                    // Получаем первую запись в каждой группе для доступа к workday
                    $firstSchedule = $groupedSchedules->first();
                @endphp

                <!-- Отображаем первую строку для данного workday_id -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap" rowspan="1">{{ $workdayId }}</td>
                    <td class="px-6 py-4 whitespace-nowrap" rowspan="1">Расписание на {{ \Carbon\Carbon::parse($firstSchedule->workday->date)->format('d.m.y') ?? 'Не задано' }}</td>

                    <!-- Объединяем все кафедры и группы для данного дня в один выпадающий список -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <details>
                            <summary class="cursor-pointer text-indigo-600 hover:text-indigo-900">Просмотреть кафедры и группы</summary>
                            <ul class="ml-4 mt-2 list-disc">
                                @foreach ($groupedSchedules->groupBy('department.name') as $departmentName => $departmentSchedules)
                                    <li class="font-semibold">{{ $departmentName }}</li>
                                    <ul class="ml-4 list-decimal">
                                        @foreach ($departmentSchedules->groupBy('group.name') as $groupName => $groupSchedules)
                                            @php
                                                $lessonId = $groupSchedules->first()->lesson_id ?? null;
                                                $emptyLessonCount = 0;

                                                if ($lessonId) {
                                                    $lesson = \App\Models\Lesson::find($lessonId);

                                                    if ($lesson) {
                                                        $emptyLessonCount = collect($lesson->getAttributes())
                                                            ->filter(function ($value, $key) {
                                                                return str_ends_with($key, '_is_empty') && (bool)$value === true;
                                                            })
                                                            ->count();
                                                    }
                                                }

                                                $availableLessons = 7 - $emptyLessonCount;
                                                $departmentId = $departmentSchedules->first()->department->id;
                                                $groupId = $groupSchedules->first()->group->id;
                                            @endphp
                                            <li>
                                                {{ $groupName }} - {{ $availableLessons }} пар(ы)
                                                <a href="{{ route('schedules.edit', [
                                                    'schedule' => $firstSchedule->id,
                                                    'departmentId' => $departmentId,
                                                    'groupId' => $groupId,
                                                    'lessonId' => $lessonId
                                                ]) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                                    Редактировать пары
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </ul>
                        </details>
                    </td>

                    <!-- Отображаем ссылку для редактирования всего расписания дня -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a target="_blank" href="{{ route('preview') }}" class="text-indigo-600 hover:text-indigo-900">
                            Просмотр расписания
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
