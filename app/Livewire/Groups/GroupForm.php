<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Department;
use Livewire\Component;

class GroupForm extends Component
{
    public $group;
    public $name;
    public $department_id; // ID выбранной кафедры
    public $departments; // Все доступные кафедры

    public function mount(Group $group = null)
    {
        $this->group = $group ?: new Group();
        $this->name = $this->group->name;
        $this->department_id = $this->group->department_id;
        $this->departments = Department::all(); // Загружаем все кафедры
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $this->group->name = $this->name;
        $this->group->department_id = $this->department_id;
        $this->group->save();

        session()->flash('message', $this->group->exists ? 'Группа успешно обновлена.' : 'Группа успешно добавлена.');

        return redirect()->route('groups.index');
    }

    public function render()
    {
        return view('livewire.groups.group-form', [
            'departments' => $this->departments,
        ]);
    }
}
