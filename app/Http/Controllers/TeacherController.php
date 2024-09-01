<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teachers.index');
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', ['teacher' => $teacher]);
    }
}
