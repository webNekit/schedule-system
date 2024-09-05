<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $pageTitle }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @livewire('schedules.schedule-form', [
                        'schedule' => $schedule,
                        'departments' => $departments,
                        'groups' => $groups,
                        'subjects' => $subjects,
                        'teachers' => $teachers,
                        'rooms' => $rooms,
                        'workdays' => $workdays,
                        'selectedDepartment' => $selectedDepartment,
                        'selectedGroup' => $selectedGroup,
                        'selectedLesson' => $selectedLesson
                    ])                                  
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- lesson_i d должен выбираться не по группе, мы его должны получить по переданному id, напомню вот поля в таблице schedules
'workday_id', 'department_id', 'group_id', 'lesson_id' --}}