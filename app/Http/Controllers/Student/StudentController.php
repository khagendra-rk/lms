<?php

namespace App\Http\Controllers\Student;

use App\Models\Borrow;
use App\Models\Student;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\MediaService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::with('faculty:id,name')->paginate(10);

        return response()->json($students);
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
            'email'         => ['required', 'email', 'unique:students,email'],
            'college_email' => ['required', 'email', 'unique:students,college_email'],
            'faculty_id'    => ['required', 'exists:faculties,id'],
            'parent_name'   => ['required'],
            'parent_contact' => ['required', 'integer', 'digits:10', 'regex:/((98)|(97))(\d){8}/'],
            'year'           => ['required', 'integer', 'min:' . config('app.year')],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,gif'],
            'registration_no' => ['nullable'],
            'symbol_no'      => ['required'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = MediaService::upload($request->file('image'), "students");
        }

        $student = Student::create($data);

        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $student->load(['faculty:id,name', 'borrows']);

        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name'          => ['required'],
            'phone_no'      => ['required', 'integer', 'digits:10', 'regex:/((98)|(97))(\d){8}/'],
            'address'       => ['required'],
            'email'         => ['required', 'email', 'unique:students,email'],
            'college_email' => ['required', 'email', 'unique:students,college_email'],
            'faculty_id'    => ['required', 'exists:faculties,id'],
            'parent_name'   => ['required'],
            'parent_contact' => ['required', 'integer', 'digits:10', 'regex:/((98)|(97))(\d){8}/'],
            'year'           => ['required', 'integer', 'min:' . config('app.year')],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,gif'],
            'registration_no' => ['nullable'],
            'symbol_no'      => ['nullable'],
        ]);
        if ($request->hasFile('image')) {
            if (!empty($student->image)) {
                Storage::delete('public/' . $student->image);
            }

            $data['image'] = MediaService::upload($request->file('image'), "students");
        } else {
            unset($data['image']);
        }

        $student->update($data);
        $student->fresh();
        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->noContent();
    }

    public function documents(Student $student)
    {
        $documents = $student->documents;

        return response()->json($documents);
    }

    public function storeDocument(Request $request, Student $student)
    {
        $request->validate([
            'type' => ['required'],
            'document' => ['required', 'file'],
        ]);

        $file = MediaService::upload($request->file('document'), 'documents');

        $document = Document::create([
            'student_id' => $student->id,
            'type' => $request->type,
            'file' => $file,
        ]);

        return response()->json($document);
    }

    public function updateDocument(Request $request, Student $student, Document $document)
    {
        $request->validate([
            'type' => ['nullable'],
            'document' => ['nullable', 'file'],
        ]);

        /**
         * Due to PHP issue of not populating $_FILES variable when request is not
         * both multipart/form-data AND POST method, we cannot use Postman's or Axios's
         * PUT or PATCH method. Instead send request as POST but include "_method" = "PUT"
         * or "_METHOD" = "PATCH" when sending form request.
         *
         * This only applies when sending FILES via PUT or PATCH method. You can send text
         * input just fine.
         *
         * For more information see this: https://stackoverflow.com/a/65009135
         * or https://stackoverflow.com/a/65009227
         */
        if ($request->hasFile('document')) {
            if ($document->file) {
                Storage::delete('public/' . $document->file);
            }
            $file = MediaService::upload($request->file('document'), 'documents');
        } else {
            $file = $document->file;
        }

        $document->update([
            'type' => empty($request->type) ? $document->type : $request->type,
            'file' => $file,
        ]);
        $document->fresh();

        return response()->json($document);
    }

    public function showDocument(Student $student, Document $document)
    {
        return response()->json($document);
    }

    public function destroyDocument(Student $student, Document $document)
    {
        $document->delete();

        return response()->noContent();
    }

    public function bookingRequest(Student $student)
    {
        $bookings = Borrow::whereNotNull('booking_at')->whereNull('issued_at')->get();

        return response()->json($bookings);
    }
}
