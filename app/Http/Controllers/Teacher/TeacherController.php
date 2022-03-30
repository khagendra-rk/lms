<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::paginate(10);

        return response()->json($teachers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required'],
            'phone_no'      => ['required', 'integer', 'digits:10', 'regex:/((98)|(97))(\d){8}/'],
            'address'       => ['required'],
            'email'         => ['required', 'email', 'unique:teachers,email'],
            'college_email' => ['nullable', 'email', 'unique:teachers,college_email'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,gif'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = MediaService::upload($request->file('image'), "teachers");
        }

        $teacher = Teacher::create($data);

        return response()->json($teacher, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name'          => ['required'],
            'phone_no'      => ['required', 'integer', 'digits:10', 'regex:/((98)|(97))(\d){8}/'],
            'address'       => ['required'],
            'email'         => ['required', 'email', 'unique:teachers,email'],
            'college_email' => ['required', 'email', 'unique:teachers,college_email'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,gif'],
        ]);

        if ($request->hasFile('image')) {
            if (!empty($teacher->image)) {
                Storage::delete('public/' . $teacher->image);
            }

            $data['image'] = MediaService::upload($request->file('image'), "teachers");
        } else {
            unset($data['image']);
        }

        $teacher->update($data);
        $teacher->fresh();
        return response()->json($teacher);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return response()->noContent();
    }
}
