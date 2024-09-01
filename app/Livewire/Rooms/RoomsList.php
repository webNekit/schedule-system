<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class RoomsList extends Component
{

    use WithPagination;

    #[Computed()]
    public function rooms()
    {
        return Room::paginate(10);
    }

    public function delete($id)
    {
        $room = Room::find($id);
        $room->delete();

        session()->flash('message', 'Кабинет удален успешно.');
        $this->render();
    }

    public function render()
    {
        return view('livewire.rooms.rooms-list', [
            'rooms' => $this->rooms,
        ]);
    }
}
