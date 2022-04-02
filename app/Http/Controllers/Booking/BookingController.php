<?php

namespace App\Http\Controllers\Booking;

use App\Models\Index;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        $query = Borrow::whereNull('issued_at')->whereNotNull('booked_at');
        if (auth()->user()->teacher) {
            $query = $query->where('teacher_id', auth()->user()->teacher->id);
        } else {
            $query = $query->where('student_id', auth()->user()->student->id);
        }

        $query = $query->whereNull('returned_at');

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        // Check If Book has Index Available
        $index = Index::where('book_id', $request->book_id)->where('is_borrowed', false)->first();
        if (!$index) {
            throw ValidationException::withMessages([
                'book_id' => ['There are no books available to borrow!'],
            ]);
        }

        $borrow = DB::transaction(function () use ($index, $request) {
            $user = auth()->user();
            $data = ['index_id' => $index->id];
            if (auth()->user()->teacher) {
                $data['teacher_id'] = auth()->user()->teacher->id;
            } else {
                $data['student_id'] = auth()->user()->student->id;
            }
            $data['booked_at'] = now();

            $index->update(['is_borrowed' => true]);
            $data['issued_by'] = auth()->id();

            return Borrow::create($data);
        });

        return response()->json($borrow);
    }

    public function cancel(Borrow $borrow)
    {
        if (auth()->user()->teacher) {
            if (auth()->user()->teacher->id != $borrow->teacher_id) {
                throw ValidationException::withMessages([
                    'teacher_id' => ['You are not the person who have booked this book!'],
                ]);
            }
        } else {
            if (auth()->user()->student->id != $borrow->student_id) {
                throw ValidationException::withMessages([
                    'student_id' => ['You are not the person who have booked this book!'],
                ]);
            }
        }

        if (!empty($borrow->issued_at)) {
            throw ValidationException::withMessages([
                'issued_at' => ['You have already taken the book from libaray. Please return this to cancel your borrow!'],
            ]);
        }

        if (empty($borrow->booked_at)) {
            throw ValidationException::withMessages([
                'booked_at' => ['You have already taken the book from libaray. Please return this to cancel your borrow!'],
            ]);
        }

        $borrow->update([
            'returned_at' => now(),
        ]);
        $borrow->index->update(['is_borrowed' => false]);

        return response()->noContent();
    }
}
