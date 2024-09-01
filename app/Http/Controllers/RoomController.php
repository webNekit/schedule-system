<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index', [
            'pageTitle' => 'Кабинеты',
            'buttonTitle' => 'Добавить кабинет',
        ]);
    }

    public function create()
    {
        return view('rooms.create', [
            'pageTitle' => 'Новый кабинет',
            'buttonTitle' => 'Добавить кабинет',
        ]);
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', [
            'room' => $room,
            'pageTitle' => 'Редактировать кабинет',
            'buttonTitle' => 'Добавить кабинет',
        ]);
    }
}
