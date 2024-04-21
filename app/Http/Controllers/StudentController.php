<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use domain\Facades\StudentFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        return Inertia::render('Student/Index');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);

            $request['image_path'] = $imageName;
        } else {
            return redirect()->back()->with('error', 'No image uploaded.');
        }

        return StudentFacade::store($request->all());
    }

    public function list()
    {
        $students = StudentFacade::all();
        return response()->json($students);
    }

    public function get($student_id)
    {
        $students = StudentFacade::get($student_id);
        return response()->json($students);
    }

    public function delete($student_id)
    {
        return StudentFacade::delete($student_id);
    }

    public function statusChange($student_id)
    {
        return StudentFacade::statusChange($student_id);
    }

    public function update(Request $details, $student_id)
    {
        return StudentFacade::update($details->all(), $student_id);
    }
}
