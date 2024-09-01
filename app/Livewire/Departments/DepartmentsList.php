<?php

namespace App\Livewire\Departments;

use App\Models\Department;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentsList extends Component
{

    use WithPagination;

    #[Computed()]
    public function departments()
    {
        return Department::paginate(10);
    }

    public function delete($id)
    {
        $room = Department::find($id);
        $room->delete();

        session()->flash('message', 'Кабинет удален успешно.');
        $this->render();
    }

    public function render()
    {
        return view('livewire.departments.departments-list', [
            'departments' => Department::with('groups')->paginate(10), // Подгружаем связанные группы с кафедрами
        ]);
    }
}
