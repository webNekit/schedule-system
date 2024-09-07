<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Department;
use App\Models\Subject; // Добавляем модель Subject
use Livewire\Component;

class GroupForm extends Component
{
    public $group;
    public $name;
    public $department_id; // ID выбранной кафедры
    public $departments; // Все доступные кафедры

    public $allSubjects; // Все доступные дисциплины
    public $addedSubjects = []; // Список добавленных дисциплин

    public function mount(Group $group = null)
    {
        $this->group = $group ?: new Group();
        $this->name = $this->group->name;
        $this->department_id = $this->group->department_id;
        $this->departments = Department::all(); // Загружаем все кафедры
        $this->allSubjects = Subject::all(); // Загружаем все дисциплины

        // Преобразуем subjects_id в массив
        $this->addedSubjects = json_decode($this->group->subjects_id, true) ?? [];
    }

    public function addSubject($subjectId)
    {
        if (!in_array($subjectId, $this->addedSubjects)) {
            $this->addedSubjects[] = $subjectId;
        }
    }

    public function removeSubject($subjectId)
    {
        $this->addedSubjects = array_filter($this->addedSubjects, function ($id) use ($subjectId) {
            return $id != $subjectId;
        });
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $this->group->name = $this->name;
        $this->group->department_id = $this->department_id;
        $this->group->subjects_id = json_encode($this->addedSubjects); // Сохраняем в формате JSON

        // Устанавливаем значение по умолчанию для weekly_hours при создании новой группы
        if (!$this->group->exists) {
            $this->group->weekly_hours = 36; // Значение по умолчанию
        }

        $this->group->save();

        session()->flash('message', $this->group->exists ? 'Группа успешно обновлена.' : 'Группа успешно добавлена.');

        return redirect()->route('groups.index');
    }

    public function render()
    {
        return view('livewire.groups.group-form', [
            'departments' => $this->departments,
            'allSubjects' => $this->allSubjects,
        ]);
    }
}
