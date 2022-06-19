<?php

namespace App\Http\Controllers\Book;

use App\Models\Book;
use App\Models\Index;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * It returns a JSON response of all the books in the database, paginated by 10.
     * 
     * @return A JSON response of all the books in the database.
     */
    public function index()
    {
        $books = Book::paginate(10);

        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(Book::class);
        $data = $request->validate([
            'name'          => ['required'],
            'author'        => ['required'],
            'publication'   => ['required'],
            'edition'       => ['required'],
            'published_year' => ['required', 'integer'],
            'price'         => ['required', 'integer'],
            'prefix'        => ['required'],
            'added_by'      => ['nullable'],
            'book_type'     => ['required'],
            'faculties'     => ['required', 'array'],
            'faculties.*'   => ['required', 'integer', 'exists:faculties,id'],
        ]);

        $data['added_by'] = auth()->id();

        $book = Book::create($data);
        $book->faculties()->sync($request->faculties);
        $book->fresh();
        $book->load('faculties');

        return response()->json(['message' => 'Book Created Successfully!', 'book' => $book], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    /**
     * It loads the book with the faculties that are associated with it
     * 
     * @param Book book The model instance that the policy applies to.
     * 
     * @return A JSON object of the book.
     */
    public function show(Book $book)
    {
        $this->authorize($book);
        $book->load('faculties');

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $this->authorize($book);
        $data = $request->validate([
            'name'          => ['nullable'],
            'author'        => ['nullable'],
            'publication'   => ['nullable'],
            'edition'       => ['nullable'],
            'published_year' => ['nullable', 'integer'],
            'price'         => ['nullable', 'integer'],
            'prefix'        => ['nullable'],
            'added_by'      => ['nullable'],
            'book_type'     => ['nullable'],
            'faculties'     => ['nullable', 'array'],
            'faculties.*'   => ['nullable', 'integer', 'exists:faculties,id'],
        ]);

        // if there is no array items
        // $keys = array_keys($data);

        // $keys = ['name', 'author', 'publication'];
        // foreach ($keys as $key) {
        //     if (empty($data[$key])) {
        //         unset($data[$key]);
        //     }
        // }

        $book->update($data);
        $book->faculties()->sync($request->faculties);

        $book->fresh();
        $book->load('faculties');

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $this->authorize($book);
        $book->delete();

        return response()->noContent();
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'search' => ['required'],
        ]);

        $books = Book::where('name', 'like', '%' . $data['search'] . '%')
            // ->orWhere('author', 'like', '%' . $data['search'] . '%')
            // ->orWhere('publication', 'like', '%' . $data['search'] . '%')
            // ->orWhere('edition', 'like', '%' . $data['search'] . '%')
            // ->orWhere('prefix', 'like', '%' . $data['search'] . '%')
            // ->orWhere('book_type', 'like', '%' . $data['search'] . '%')
            ->paginate(10);

        return response()->json($books);
    }

    public function faculties(Book $book)
    {
        $faculties = $book->faculties;

        return response()->json($faculties);
    }

    public function removeFaculty(Book $book, Request $request)
    {
        $data = $request->validate([
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
        ]);

        $book->faculties()->detach($data['faculty_id']);

        return response()->noContent();
    }

    public function addFaculty(Book $book, Request $request)
    {
        $data = $request->validate([
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
        ]);

        $existing = $book->faculties->pluck('id')->toArray();
        if (in_array($data['faculty_id'], $existing)) {
            throw ValidationException::withMessages([
                'faculty_id' => ['The faculty is already added to this book.'],
            ]);
        }

        $book->faculties()->attach($data['faculty_id']);

        return response()->noContent();
    }

    /**
     * It returns a paginated list of indices for a given book
     * 
     * @param Book book The book object
     * 
     * @return A collection of indices.
     */
    public function bookIndices(Book $book)
    {
        $indices = Index::where('book_id', $book->id)->paginate(30);

        return response()->json($indices);
    }

    /**
     * It creates a new index for a book
     * 
     * @param Request request The request object.
     * @param Book book The book model
     * 
     * @return The index is being returned.
     */
    public function addIndex(Request $request, Book $book)
    {
        $this->authorize('create', $book);
        $request->validate([
            'code'    => ['required'],
        ]);

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
     * It updates the index code of a book.
     * 
     * @param Request request The request object.
     * @param Book book The book model
     * @param Index index the index that is being updated
     * 
     * @return The updated index.
     */
    public function updateIndex(Request $request, Book $book, Index $index)
    {
        $this->authorize('update', $book);

        $request->validate([
            'code'    => ['required'],
        ]);

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
            'code' => $request->code,
        ]);

        $index->fresh();

        return response()->json($index);
    }

    /**
     * It deletes the index of a book if it is not borrowed
     * 
     * @param Book book The book model instance.
     * @param Index index The index of the book
     */
    public function destroyIndex(Book $book, Index $index)
    {
        $this->authorize('delete', $book);

        if ($index->is_borrowed) {
            return response()->json([
                'error' => 'This book index is currently borrowed. You cannot delete this now!',
            ]);
        }

        $index->delete();

        return response()->noContent();
    }

    /**
     * It adds a quantity of indices to a book
     * 
     * @param Book book The book object
     * @param Request request 
     * 
     * @return The response is a JSON object.
     */
    public function addQuantityIndex(Book $book, Request $request)
    {
        $this->authorize('create', $book);

        $request->validate([
            'quantity' => ['required', 'integer', 'gte:1'],
        ]);

        // Get Highest Book ID

        $latest_index = Index::orderBy('code', 'DESC')->first()->code;
        if (empty($latest_index)) {
            $latest_index = 0;
        }

        $indices = [];
        $now = now();
        for ($i = 1; $i <= $request->quantity; $i++) {
            $indices[] = [
                'book_id' => $book->id,
                'book_prefix'  => $book->prefix,
                'code'    => $i + $latest_index,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $all_indices = Index::insert($indices);

        return response()->json($all_indices);
    }

    /**
     * It adds a range of indices to a book
     * 
     * @param Book book The book model
     * @param Request request 
     * 
     * @return The response is a JSON object containing the newly created indices.
     */
    public function addRangeIndex(Book $book, Request $request)
    {
        $this->authorize('create', $book);

        $request->validate([
            'min' => ['required', 'integer', 'gte:1'],
            'max' => ['required', 'integer', 'gt:min'],
        ]);

        // Get Highest Book ID
        $range = range($request->min, $request->max);
        $count = Index::where('book_id', $book->id)->whereIn('code', $range)->count();

        if ($count != 0) {
            throw ValidationException::withMessages([
                'min' => ['There are already books registed with code within the range provided!'],
                'max' => ['There are already books registed with code within the range provided!'],
            ]);
        }

        $indices = [];
        $now = now();
        foreach ($range as $r) {
            $indices[] = [
                'book_id' => $book->id,
                'book_prefix'  => $book->prefix,
                'code'    => $r,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Index::insert($indices);

        $all_indices = Index::where('book_id', $book->id)
            ->whereIn('code', $range)
            ->get();


        return response()->json($all_indices);
    }

    /**
     * It takes a book and a request, validates the request, checks if the book has any indices with
     * the same code, if not, it creates the indices and returns the indices
     * 
     * @param Book book the book object
     * @param Request request 
     * 
     * @return The response is a JSON object containing the newly created indices.
     */
    public function addListIndex(Book $book, Request $request)
    {
        $this->authorize('create', $book);
        $request->validate([
            'codes' => ['required', 'array'],
            'codes.*' => ['required', 'integer'],
        ]);

        // Get Book with Given Codes
        $code_indices = Index::where('book_id', $book->id)->whereIn('code', $request->codes)->get();

        if (count($code_indices) != 0) {
            $codes = implode(", ", $code_indices->pluck('code')->toArray());

            throw ValidationException::withMessages([
                'codes' => ['There are already books registed with code provided! Conflicted codes are: ' . $codes],
            ]);
        }

        $indices = [];
        $now = now();
        foreach ($request->codes as $r) {
            $indices[] = [
                'book_id' => $book->id,
                'book_prefix'  => $book->prefix,
                'code'    => $r,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Index::insert($indices);
        $all_indices = Index::where('book_id', $book->id)
            ->whereIn('code', $request->codes)
            ->get();

        return response()->json($all_indices);
    }
}
