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

class ScheduleForm extends Component
{
    public $schedule;
    public $workday_id;
    public $department_id;
    public $group_id;
    public $lesson_id;
    public $is_active = false;
    public $is_archive = false;
    public $lessons = []; // Массив для хранения данных уроков

    public $departments;
    public $groups = [];
    public $subjects;
    public $teachers;
    public $rooms;
    public $workdays;

    public $selectedDepartment;
    public $selectedGroup;
    public $selectedLesson;

    public $remainingHours = []; // Остаток часов для выбранных дисциплин

    public function mount($schedule, $departments, $groups, $subjects, $teachers, $rooms, $workdays, $selectedDepartment, $selectedGroup, $selectedLesson)
    {
        $this->departments = $departments;
        $this->groups = $groups;
        $this->subjects = $subjects;
        $this->teachers = $teachers;
        $this->rooms = $rooms;
        $this->workdays = $workdays;

        $this->selectedDepartment = $selectedDepartment;
        $this->selectedGroup = $selectedGroup;
        $this->selectedLesson = $selectedLesson;

        if ($schedule) {
            $this->schedule = $schedule;
            $this->workday_id = $schedule->workday_id;
            $this->department_id = $selectedDepartment->id;
            $this->group_id = $selectedGroup->id;
            $this->lesson_id = $selectedLesson->id;
            $this->is_active = $schedule->is_active;
            $this->is_archive = $schedule->is_archive;

            // Определяем семестр и фильтруем дисциплины
            $semesterId = $this->determineCurrentSemester();
            if ($semesterId) {
                $this->subjects = Subject::where('semester_id', $semesterId)->get();
            } else {
                $this->subjects = collect(); // Очищаем список дисциплин, если семестр не найден
            }

            // Заполняем данные уроков для выбранной группы
            $this->fillLessons($selectedLesson);
        } else {
            $this->initializeEmptyLessons();
        }

        // Вычисляем оставшиеся часы
        $this->recalculateRemainingHours();
    }

    public function updatedDepartmentId()
    {
        // Когда выбирается другая кафедра, обновляем список групп
        $this->groups = Group::where('department_id', $this->department_id)->get();
        $this->group_id = null; // Сбросить выбранную группу при изменении кафедры
    }

    public function updatedLessons($propertyName)
    {
        // Обновляем оставшиеся часы при изменении дисциплины
        if (strpos($propertyName, 'lessons.') !== false) {
            $this->recalculateRemainingHours();
        }
    }

    public function fillLessons($lesson)
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->lessons[$i] = [
                'subject_id' => $lesson->{"{$i}_subject_id"} ?? null,
                'teacher_id' => $lesson->{"{$i}_teacher_id"} ?? null,
                'room_id' => $lesson->{"{$i}_room_id"} ?? null,
                'is_empty' => (bool)($lesson->{"{$i}_is_empty"} ?? false) // Приведение к булевому типу
            ];
        }

        // Пересчитываем оставшиеся часы после заполнения уроков
        $this->recalculateRemainingHours();
    }

    private function initializeEmptyLessons()
    {
        // Инициализация пустых данных уроков
        for ($i = 1; $i <= 7; $i++) {
            $this->lessons[$i] = [
                'subject_id' => null,
                'teacher_id' => null,
                'room_id' => null,
                'is_empty' => false
            ];
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

    private function recalculateRemainingHours()
    {
        $this->remainingHours = []; // Очистка старых значений

        foreach ($this->lessons as $lesson) {
            if (isset($lesson['subject_id']) && !empty($lesson['subject_id'])) {
                $subjectId = $lesson['subject_id'];
                $subject = Subject::find($subjectId);
                if ($subject) {
                    // Предположим, что дисциплина имеет общее количество часов
                    $totalHours = $subject->total_hours;
                    $hoursBooked = 0;

                    // Пересчитываем количество часов, которые уже добавлены
                    foreach ($this->lessons as $lesson) {
                        if (isset($lesson['subject_id']) && $lesson['subject_id'] === $subjectId) {
                            $hoursBooked += 2; // Предполагаем, что на каждую пару тратится 2 часа
                        }
                    }

                    $this->remainingHours[$subjectId] = max(0, $totalHours - $hoursBooked);
                }
            }
        }
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

        // Подготовка данных для создания или обновления записи в таблице Lesson
        $lessonData = [];
        $subjectIds = [];
        for ($i = 1; $i <= 7; $i++) {
            if (!$this->lessons[$i]['is_empty'] && $this->lessons[$i]['subject_id']) {
                $subjectIds[] = $this->lessons[$i]['subject_id'];
            }
            $lessonData["{$i}_subject_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['subject_id'];
            $lessonData["{$i}_teacher_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['teacher_id'];
            $lessonData["{$i}_room_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['room_id'];
            $lessonData["{$i}_is_empty"] = $this->lessons[$i]['is_empty'];
        }

        if ($this->schedule) {
            // Проверяем, если существует текущая запись расписания (schedule)
            $schedule = Schedule::find($this->schedule->id); // Находим конкретное расписание по его ID
            if ($schedule) {
                $lesson = Lesson::find($schedule->lesson_id); // Получаем связанную запись урока
                if ($lesson) {
                    $lesson->update($lessonData); // Обновляем существующий урок
                } else {
                    $lesson = Lesson::create($lessonData); // Создаем новый урок
                    $schedule->lesson_id = $lesson->id; // Связываем урок с расписанием
                }

                $schedule->update([
                    'workday_id' => $this->workday_id,
                    'department_id' => $this->department_id,
                    'group_id' => $this->group_id,
                    'lesson_id' => $lesson->id,
                    'is_active' => $this->is_active,
                    'is_archive' => $this->is_archive,
                ]);
            }
        } else {
            $lesson = Lesson::create($lessonData);
            Schedule::create([
                'workday_id' => $this->workday_id,
                'department_id' => $this->department_id,
                'group_id' => $this->group_id,
                'lesson_id' => $lesson->id,
                'is_active' => $this->is_active,
                'is_archive' => $this->is_archive,
            ]);
        }

        // Обновление оставшихся часов по предметам
        foreach ($subjectIds as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                $subject->total_hours = max(0, $subject->total_hours - 2);
                $subject->save();
            }
        }

        // Обновление weekly_hours для группы
        $group = Group::find($this->group_id);
        if ($group) {
            $group->weekly_hours = max(0, $group->weekly_hours - (count($subjectIds) * 2));
            $group->save();
        }

        session()->flash('message', 'Расписание успешно сохранено.');

        // Редирект на страницу списка расписаний
        return redirect()->route('schedules.index');
    }

    public function render()
    {
        return view('livewire.schedules.schedule-form', [
            'departments' => $this->departments,
            'groups' => $this->groups,
            'subjects' => $this->subjects,
            'teachers' => $this->teachers,
            'rooms' => $this->rooms,
            'remainingHours' => $this->remainingHours,
        ]);
    }
}
