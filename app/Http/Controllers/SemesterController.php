<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        return view('semester.index');
    }

    public function create()
    {
        return view('semester.create');
    }

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        return view('semester.edit', compact('semester'));
    }
}
