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
    public $lessons = [];

    public $departments;
    public $groups = [];
    public $subjects = [];
    public $teachers;
    public $rooms;
    public $workdays;

    public $selectedDepartment;
    public $selectedGroup;
    public $selectedLesson;

    public $remainingHours = [];
    public $noSubjectsMessage = '';

    public function mount($schedule, $departments, $teachers, $rooms, $workdays, $selectedDepartment, $selectedGroup, $selectedLesson)
    {
        $this->departments = $departments;
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

            // Обновляем группы и дисциплины при загрузке компонента
            $this->groups = Group::where('department_id', $this->department_id)->get();
            $this->updateSubjects(); // Обновляем дисциплины для выбранной группы

            // Заполняем данные уроков для выбранного занятия
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
        $this->group_id = null;
        $this->subjects = collect(); // Очищаем список дисциплин
        $this->noSubjectsMessage = ''; // Очищаем сообщение
    }

    public function updatedGroupId()
    {
        $this->updateSubjects(); // Обновление дисциплин после выбора группы
        $this->initializeEmptyLessons(); // Инициализация пустых уроков
    }

    public function updatedLessons($propertyName)
    {
        if (strpos($propertyName, 'lessons.') !== false) {
            $this->recalculateRemainingHours();
        }
    }

    private function updateSubjects()
    {
        $semesterId = $this->determineCurrentSemester(); // Определяем текущий семестр

        if ($this->group_id && $semesterId) {
            $group = Group::find($this->group_id);

            if ($group) {
                // Проверяем, что subjects_id является массивом или пустым массивом
                $subjectIds = is_array($group->subjects_id) ? $group->subjects_id : json_decode($group->subjects_id, true);

                if (is_null($subjectIds)) {
                    $subjectIds = [];
                }

                // Получаем дисциплины для выбранной группы и семестра
                $this->subjects = Subject::whereIn('id', $subjectIds)
                    ->where('semester_id', $semesterId)
                    ->get();

                if ($this->subjects->isEmpty()) {
                    $this->noSubjectsMessage = 'Для выбранной группы нет привязанных дисциплин.';
                } else {
                    $this->noSubjectsMessage = ''; // Очистка сообщения
                }

                logger()->info('Subjects updated for group_id: ' . $this->group_id, ['subjects' => $this->subjects->toArray()]);
            } else {
                $this->subjects = collect();
                $this->noSubjectsMessage = 'Для выбранной группы нет привязанных дисциплин.';
                logger()->info('Group not found for group_id: ' . $this->group_id);
            }
        } else {
            $this->subjects = collect();
            $this->noSubjectsMessage = 'Для выбранной группы нет привязанных дисциплин.';
        }
    }

    public function fillLessons($lesson)
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->lessons[$i] = [
                'subject_id' => $lesson->{"{$i}_subject_id"} ?? null,
                'teacher_id' => $lesson->{"{$i}_teacher_id"} ?? null,
                'room_id' => $lesson->{"{$i}_room_id"} ?? null,
                'is_empty' => (bool)($lesson->{"{$i}_is_empty"} ?? false)
            ];
        }

        $this->recalculateRemainingHours();
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

    private function determineCurrentSemester()
    {
        $workday = Workday::find($this->workday_id);

        if (!$workday || !$workday->date) {
            return null;
        }

        $workdayDate = new \DateTime($workday->date);
        $month = $workdayDate->format('m');

        $semester = Semester::whereMonth('start_date', '<=', $month)
            ->whereMonth('end_date', '>=', $month)
            ->first();

        return $semester ? $semester->id : null;
    }

    private function recalculateRemainingHours()
    {
        $this->remainingHours = [];

        foreach ($this->lessons as $lesson) {
            if (isset($lesson['subject_id']) && !empty($lesson['subject_id'])) {
                $subjectId = $lesson['subject_id'];
                $subject = Subject::find($subjectId);
                if ($subject) {
                    $totalHours = $subject->total_hours;
                    $hoursBooked = 0;

                    foreach ($this->lessons as $lesson) {
                        if (isset($lesson['subject_id']) && $lesson['subject_id'] === $subjectId) {
                            $hoursBooked += 2;
                        }
                    }

                    $this->remainingHours[$subjectId] = max(0, $totalHours - $hoursBooked);
                }
            }
        }
    }

    public function save()
    {
        // Валидация данных
        $this->validate([
            'workday_id' => 'required|exists:workdays,id',
            'department_id' => 'required|exists:departments,id',
            'group_id' => 'required|exists:groups,id',
            'lessons.*.subject_id' => 'nullable|exists:subjects,id',
            'lessons.*.teacher_id' => 'nullable|exists:teachers,id',
            'lessons.*.room_id' => 'nullable|exists:rooms,id',
            'lessons.*.is_empty' => 'boolean',
        ]);

        // Подготовка данных для сохранения
        $lessonData = [];
        for ($i = 1; $i <= 7; $i++) {
            $lessonData["{$i}_subject_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['subject_id'];
            $lessonData["{$i}_teacher_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['teacher_id'];
            $lessonData["{$i}_room_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['room_id'];
            $lessonData["{$i}_is_empty"] = $this->lessons[$i]['is_empty'];
        }

        try {
            if ($this->schedule) {
                $schedule = Schedule::find($this->schedule->id);
                if ($schedule) {
                    // Обновление данных занятия
                    $lesson = Lesson::find($schedule->lesson_id);
                    if ($lesson) {
                        $lesson->update($lessonData);
                    } else {
                        // Создание нового занятия
                        $lesson = Lesson::create($lessonData);
                        $schedule->lesson_id = $lesson->id;
                    }

                    // Обновление расписания
                    $schedule->update([
                        'workday_id' => $this->workday_id,
                        'department_id' => $this->department_id,
                        'group_id' => $this->group_id,
                        'is_active' => $this->is_active,
                        'is_archive' => $this->is_archive,
                        'lesson_id' => $lesson->id,
                    ]);

                    session()->flash('success', 'Расписание успешно обновлено.');
                } else {
                    session()->flash('error', 'Не удалось найти расписание.');
                }
            } else {
                // Создание нового расписания
                $lesson = Lesson::create($lessonData);

                $this->schedule = Schedule::create([
                    'workday_id' => $this->workday_id,
                    'department_id' => $this->department_id,
                    'group_id' => $this->group_id,
                    'is_active' => $this->is_active,
                    'is_archive' => $this->is_archive,
                    'lesson_id' => $lesson->id,
                ]);

                session()->flash('success', 'Расписание успешно создано.');
            }

            // Выполнение редиректа
            return redirect()->route('schedules.index');
        } catch (\Exception $e) {
            // Логируем ошибку
            logger()->error('Ошибка при сохранении расписания', ['error' => $e->getMessage()]);
            session()->flash('error', 'Произошла ошибка при сохранении расписания.');
        }
    }

    public function render()
    {
        return view('livewire.schedules.schedule-form');
    }
}
