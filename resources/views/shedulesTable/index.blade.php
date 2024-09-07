@extends('layouts.view')
@section('title', $title)
@section('content')
    <div class="max-w-8xl mx-auto p-4">
        @auth
            <!-- Блок для авторизованного пользователя -->
            <div class="border border-slate-200 p-4 rounded-md" role="alert">
                <button id="convertBtn" class="bg-blue-600 text-white rounded-md px-4 py-2">Вывести расписание</button>
            </div>
        @endauth

        <div class="w-full" id="schedules-wrapper">
            <div class="text-center mb-4">
                <h1 class="text-2xl font-bold">{{ $schedules->first()->department->name }}</h1>
                <p class="text-gray-500">Расписание занятий на {{ \Carbon\Carbon::parse($schedules->first()->workday->date)->format('d.m.Y') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($groups as $group)
                    <div class="border rounded-lg p-4 shadow-sm bg-white">
                        <h2 class="text-xl font-semibold mb-2">Группа {{ $group->name }}</h2>
                        <div class="grid grid-cols-3 gap-2 text-sm font-medium mb-2 text-gray-700">
                            <div>Предмет</div>
                            <div>Преподаватель</div>
                            <div>Кабинет</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-gray-600">
                            @php
                                $groupLessons = $lessons->where('id', $schedules->where('group_id', $group->id)->pluck('lesson_id')->first());
                            @endphp
                            @for ($i = 1; $i <= 7; $i++)
                                @php
                                    $subjectField = $i . '_subject_id';
                                    $teacherField = $i . '_teacher_id';
                                    $roomField = $i . '_room_id';
                                    $isEmptyField = $i . '_is_empty';

                                    $subjectName = $groupLessons->pluck($subjectField)->first() ? \App\Models\Subject::find($groupLessons->pluck($subjectField)->first())->name : '-';
                                    $teacherName = $groupLessons->pluck($teacherField)->first() ? \App\Models\Teacher::find($groupLessons->pluck($teacherField)->first())->name : '-';

                                    // Получаем имя кабинета
                                    $roomName = $groupLessons->pluck($roomField)->first() ? \App\Models\Room::find($groupLessons->pluck($roomField)->first())->name : '-';

                                    $isEmpty = $groupLessons->pluck($isEmptyField)->first();
                                @endphp
                                @if ($isEmpty)
                                    <div class="border-b py-1">-</div>
                                    <div class="border-b py-1">-</div>
                                    <div class="border-b py-1">-</div>
                                @else
                                    <div class="border-b py-1 text-sm subjectName">{{ $subjectName }}</div>
                                    <div class="border-b py-1 text-sm teacherName">{{ $teacherName }}</div>
                                    <div class="border-b py-1 text-sm roomValue">{{ $roomName }}</div>
                                @endif
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
