<?php

namespace App\Livewire\Groups;

use App\Models\Department;
use App\Models\Group;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class GroupsList extends Component
{
    use WithPagination;

    #[Url]
    public $department = 'all'; // Выбранная кафедра для фильтрации, связанная с URL

    public function setDepartment($departmentId)
    {
        $this->department = $departmentId;
        $this->resetPage(); // Сбросить пагинацию при изменении фильтра
    }

    #[Computed]
    public function groups()
    {
        $query = Group::query();

        if ($this->department != 'all') {
            $query->where('department_id', $this->department);
        }

        return $query->paginate(10); // Пагинация после всех условий
    }

    #[Computed]
    public function departments()
    {
        return Department::all();
    }

    public function delete($id)
    {
        $group = Group::find($id);

        if ($group) {
            $group->delete();
            session()->flash('message', 'Группа успешно удалена.');
        }

        $this->resetPage(); // Сбросить пагинацию после удаления
    }

    public function render()
    {
        return view('livewire.groups.groups-list', [
            'groups' => $this->groups,
            'departments' => $this->departments,
        ]);
    }
}
