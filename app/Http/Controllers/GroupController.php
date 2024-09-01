<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return view('groups.index', [
            'pageTitle' => 'Группы',
            'buttonTitle' => 'Добавить группу',
        ]);
    }
    
    public function create()
    {
        return view('groups.create', [
            'pageTitle' => 'Добавить группу',
            'buttonTitle' => 'Добавить группу',
        ]);
    }
    
    public function edit(Group $group)
    {
        return view('groups.edit', [
            'group' => $group,
            'pageTitle' => 'Добавить кафедру',
            'buttonTitle' => 'Добавить кафедру',
        ]);
    }
}
