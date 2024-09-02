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
use Livewire\Component;

class ScheduleForm extends Component
{
    public $workday_id;
    public $department_id;
    public $group_id;
    public $is_active = false;
    public $is_archive = false;
    public $lessons = [];

    // Данные для выпадающих списков
    public $departments;
    public $groups = [];
    public $subjects;
    public $teachers;
    public $rooms;
    public $workdays; // Добавляем рабочие дни

    public function mount()
    {
        // Инициализация данных для выпадающих списков
        $this->departments = Department::all();
        $this->subjects = Subject::all();
        $this->teachers = Teacher::all();
        $this->rooms = Room::all();
        $this->workdays = Workday::all(); // Загружаем все рабочие дни
        
        // Инициализация пустых данных для 7 пар
        for ($i = 1; $i <= 7; $i++) {
            $this->lessons[$i] = [
                'subject_id' => null,
                'teacher_id' => null,
                'room_id' => null,
                'is_empty' => false
            ];
        }
    }

    public function updatedDepartmentId($value)
    {
        // Загрузка групп по выбранной кафедре
        $this->groups = Group::where('department_id', $value)->get();
        $this->group_id = null; // Сброс группы при изменении кафедры
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

        // Подготовка данных для создания записи в таблице Lesson
        $lessonData = [];
        for ($i = 1; $i <= 7; $i++) {
            $lessonData["pair_{$i}_subject_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['subject_id'];
            $lessonData["pair_{$i}_teacher_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['teacher_id'];
            $lessonData["pair_{$i}_room_id"] = $this->lessons[$i]['is_empty'] ? null : $this->lessons[$i]['room_id'];
        }

        // Создание записи в таблице Lesson
        $lesson = Lesson::create($lessonData);

        // Создание записи в таблице Schedule и привязка к Lesson
        Schedule::create([
            'workday_id' => $this->workday_id,
            'department_id' => $this->department_id,
            'group_id' => $this->group_id,
            'lesson_id' => $lesson->id,
            'is_active' => $this->is_active,
            'is_archive' => $this->is_archive,
        ]);

        session()->flash('message', 'Расписание успешно создано.');
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
            'workdays' => $this->workdays, // Передаем рабочие дни в шаблон
        ]);
    }
}
