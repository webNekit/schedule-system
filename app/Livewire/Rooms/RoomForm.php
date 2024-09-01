<?php 

namespace App\Livewire\Rooms;

use App\Models\Room;
use Livewire\Component;

class RoomForm extends Component
{
    public $room;
    public $name;

    public function mount(Room $room = null)
    {
        // Если $room передан, инициализируем его, иначе создаем новый
        $this->room = $room ?: new Room();
        $this->name = $this->room->name;
    }

    public function save()
    {
        // Валидация поля name
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        // Сохранение модели
        $this->room->name = $this->name;
        $this->room->save();

        // Уведомление об успешном сохранении
        session()->flash('message', $this->room->exists ? 'Кабинет успешно обновлен.' : 'Кабинет успешно добавлен.');
        
        // Перенаправление на список комнат
        return redirect()->route('rooms.index');
    }

    public function render()
    {
        return view('livewire.rooms.room-form');
    }
}
