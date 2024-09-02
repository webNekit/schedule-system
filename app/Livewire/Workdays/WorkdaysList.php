<?php

namespace App\Livewire\Workdays;

use App\Models\Workday;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class WorkdaysList extends Component
{

    use WithPagination;

    #[Computed()]
    public function workdays()
    {
        return Workday::paginate(20);
    }

    public function delete($id)
    {
        $room = Workday::find($id);
        $room->delete();

        session()->flash('message', 'Кабинет удален успешно.');
        $this->render();
    }

    public function render()
    {
        return view('livewire.workdays.workdays-list', [
            'workdays' => $this->workdays,
        ]);
    }
}
