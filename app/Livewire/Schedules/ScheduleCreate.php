<?php

namespace App\Livewire\Schedules;

use App\Models\Department;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Workday;
use App\Models\Semester;
use Livewire\Component;

class ScheduleCreate extends Component
{
    public $schedule;
    public $workday_id;
    public $department_id;
    public $group_id;
    public $lessons = [];
    public $departments;
    public $is_active = false;
    public $is_archive = false;
    public $groups = [];
    public $subjects = [];
    public $teachers;
    public $rooms;
    public $workdays;
    
    public $remainingHours = [];
    public $noSubjectsMessage = '';

    public function mount()
    {
        // Инициализация данных
        $this->departments = Department::all();
        $this->teachers = Teacher::all();
        $this->rooms = Room::all();
        $this->workdays = Workday::all();

        // Инициализация массива уроков с ключами по умолчанию
        for ($i = 1; $i <= 7; $i++) {
            $this->lessons[$i] = [
                'subject_id' => null,
                'is_empty' => false,
                'teacher_id' => null,
                'room_id' => null,
            ];
        }

        $this->updateSubjects(); // Обновляем список дисциплин
        $this->updateRemainingHours(); // Инициализация оставшихся часов
    }

    public function updatedWorkdayId()
    {
        $this->updateSubjects(); // Обновляем список дисциплин в зависимости от выбранного дня
        $this->groups = []; // Сбрасываем список групп при изменении дня
        $this->group_id = null; // Сбрасываем выбранную группу
    }

    public function updatedDepartmentId()
    {
        $this->updateGroups(); // Обновляем группы при изменении кафедры
    }

    public function updatedGroupId()
    {
        $this->updateSubjects(); // Обновляем список дисциплин при изменении группы
        $this->initializeEmptyLessons(); // Инициализация пустых уроков
    }

    public function updatedLessons($value)
    {
        $this->updateRemainingHours();
        $this->updateSubjects();
    }

    public function updateSubjects()
    {
        $semesterId = $this->determineCurrentSemester();
        if ($semesterId && $this->group_id) {
            $group = Group::find($this->group_id);

            if ($group) {
                $subjectIds = is_array($group->subjects_id) ? $group->subjects_id : json_decode($group->subjects_id, true);

                if (is_null($subjectIds)) {
                    $subjectIds = [];
                }

                // Получаем дисциплины для выбранной группы, семестра и пары
                $this->subjects = Subject::whereIn('id', $subjectIds)
                    ->where('semester_id', $semesterId)
                    ->get();

                if ($this->subjects->isEmpty()) {
                    $this->noSubjectsMessage = 'Для выбранной группы нет привязанных дисциплин.';
                } else {
                    $this->noSubjectsMessage = ''; // Очистка сообщения
                }
            } else {
                $this->subjects = collect();
                $this->noSubjectsMessage = 'Для выбранной группы нет привязанных дисциплин.';
            }
        } else {
            $this->subjects = collect();
            $this->noSubjectsMessage = 'Для выбранной группы нет привязанных дисциплин.';
        }
    }

    private function determineCurrentSemester()
    {
        // Получаем рабочий день
        $workday = Workday::find($this->workday_id);

        if (!$workday || !$workday->date) {
            // Вернуть null, если рабочий день не установлен
            return null;
        }

        $workdayDate = new \DateTime($workday->date);
        $month = $workdayDate->format('m'); // Получаем месяц в формате '01', '02', и т.д.

        // Определяем семестр по текущему месяцу
        $semester = Semester::whereMonth('start_date', '<=', $month)
                            ->whereMonth('end_date', '>=', $month)
                            ->first();

        return $semester ? $semester->id : null;
    }

    public function updateGroups()
    {
        // Загружаем группы в зависимости от выбранной кафедры
        $this->groups = Group::where('department_id', $this->department_id)->get();
        $this->group_id = null; // Сбросить выбранную группу при изменении кафедры
    }

    private function initializeEmptyLessons()
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->lessons[$i] = [
                'subject_id' => null,
                'teacher_id' => null,
                'room_id' => null,
                'is_empty' => false
            ];
        }
    }

    private function updateRemainingHours()
    {
        $subjectIds = array_filter(array_column($this->lessons, 'subject_id'));
        $subjects = Subject::whereIn('id', $subjectIds)->get()->keyBy('id');

        $this->remainingHours = $subjects->mapWithKeys(function ($subject) {
            $totalLessons = 0;
            for ($i = 1; $i <= 7; $i++) {
                if (isset($this->lessons[$i]['subject_id']) && $this->lessons[$i]['subject_id'] == $subject->id && !$this->lessons[$i]['is_empty']) {
                    $totalLessons++;
                }
            }
            $remaining = max(0, $subject->total_hours - 2 * $totalLessons);
            return [$subject->id => $remaining];
        });
    }

    public function save()
    {
        $this->validate([
            'workday_id' => 'required|exists:workdays,id',
            'department_id' => 'required|exists:departments,id',
            'group_id' => 'required|exists:groups,id',
            'lessons.*.subject_id' => 'nullable|exists:subjects,id',
            'lessons.*.teacher_id' => 'nullable|exists:teachers,id',
            'lessons.*.room_id' => 'nullable|exists:rooms,id',
            'lessons.*.is_empty' => 'boolean',
        ]);

        $lessonData = [];
        $subjectIds = [];
        $totalLessons = 0;

        for ($i = 1; $i <= 7; $i++) {
            if (!$this->lessons[$i]['is_empty']) {
                $totalLessons++;
                $subjectIds[] = $this->lessons[$i]['subject_id'];
            }
            $lessonData["{$i}_subject_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['subject_id'];
            $lessonData["{$i}_teacher_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['teacher_id'];
            $lessonData["{$i}_room_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['room_id'];
            $lessonData["{$i}_is_empty"] = $this->lessons[$i]['is_empty'];
        }

        // Создание или обновление записи в таблице Lesson
        $lesson = Lesson::create($lessonData);

        // Уменьшение total_hours у дисциплин
        foreach ($subjectIds as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                $subject->total_hours = max(0, $subject->total_hours - 2 * $totalLessons);
                $subject->save();
            }
        }

        // Уменьшение weekly_hours у группы
        $group = Group::find($this->group_id);
        if ($group) {
            $group->weekly_hours = max(0, $group->weekly_hours - 2 * $totalLessons);
            $group->save();
        }

        // Создание записи в таблице Schedule и привязка к Lesson
        Schedule::create([
            'workday_id' => $this->workday_id,
            'department_id' => $this->department_id,
            'group_id' => $this->group_id,
            'lesson_id' => $lesson->id,
        ]);

        session()->flash('message', 'Расписание успешно создано.');
        return redirect()->route('schedules.index');
    }

    public function render()
    {
        return view('livewire.schedules.schedule-create', [
            'departments' => $this->departments,
            'groups' => $this->groups,
            'subjects' => $this->subjects,
            'teachers' => $this->teachers,
            'rooms' => $this->rooms,
        ]);
    }
}
