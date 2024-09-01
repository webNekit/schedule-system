<?php

namespace App\Livewire\Departments;

use App\Models\Department;
use Livewire\Component;

class DepartmentForm extends Component
{
    public $department;
    public $name;

    public function mount(Department $department = null)
    {
        // Если $department передан, инициализируем его, иначе создаем новый
        $this->department = $department ?: new Department();
        $this->name = $this->department->name;
    }

    public function save()
    {
        // Валидация поля name
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        // Сохранение модели
        $this->department->name = $this->name;
        $this->department->save();

        // Уведомление об успешном сохранении
        session()->flash('message', $this->department->wasRecentlyCreated ? 'Кафедра успешно добавлена.' : 'Кафедра успешно обновлена.');
        
        // Перенаправление на список кафедр
        return redirect()->route('departments.index');
    }

    public function render()
    {
        return view('livewire.departments.department-form');
    }
}
