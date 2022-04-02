<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Models\Index;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indices = Index::paginate(30);

        return response()->json($indices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'code'    => ['required'],
        ]);

        $book = Book::find($request->book_id);

        $check = Index::query()
            ->where('book_prefix', $book->prefix)
            ->where('code', $request->code)
            ->first();

        if ($check) {
            throw ValidationException::withMessages([
                'code' => ['The provided code is taken.'],
            ]);
        }

        $index = Index::create([
            'book_id' => $book->id,
            'code' => $request->code,
            'book_prefix' => $book->prefix,
        ]);

        return response()->json($index);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function show(Index $index)
    {
        $index->load('book');

        return response()->json($index);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Index $index)
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'code'    => ['required'],
        ]);

        $book = Book::find($request->book_id);

        $check = Index::query()
            ->where('book_prefix', $book->prefix)
            ->where('code', $request->code)
            ->where('id', '!=', $index->id)
            ->first();

        if ($check) {
            throw ValidationException::withMessages([
                'code' => ['The provided code is taken.'],
            ]);
        }

        $index->update([
            'book_id' => $request->book_id,
            'code' => $request->code,
        ]);
        $index->fresh();

        return response()->json($index);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function destroy(Index $index)
    {
        $index->delete();

        return response()->noContent();
    }
}
