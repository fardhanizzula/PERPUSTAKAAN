<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBooksRequest;
use App\Http\Requests\UpdateBooksRequest;
use App\Models\Categories;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('books.index', [
            'title' => 'Books',
            'books' => Books::all(),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create', [
            'title' => 'Books',
            'categories' => Categories::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBooksRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBooksRequest $request)
{
    $validatedData = $request->validate([
        'title' => 'required',
        'categories_id' => 'required',
        'author' => 'required',
        'publisher' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    

    $imageName = time() . '.' . $request->image->getClientOriginalExtension();
    $request->image->storeAs('public/images', $imageName);

    $book = new Books;
    $book->title = $validatedData['title'];
    $book->categories_id = $validatedData['categories_id'];
    $book->author = $validatedData['author'];
    $book->publisher = $validatedData['publisher'];
    $book->image = $imageName;
    $book->save();

    $book->image = $imageName;
    $book->save();

    return redirect('/books')->with('success', 'New Book has been added!');
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function show(Books $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function edit(Books $books, $book)
    {
        $bookFind = Books::find($book);

        return view('books.edit',compact(['bookFind']), [
            'title' => 'Books',
            'books' => $books,
            'categories' => Categories::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBooksRequest  $request
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBooksRequest $request, Books $books, $book)
{
    $bookFind = Books::find($book);

    $validateData = $request->validate([
        'title' => 'required',
        'categories_id' => 'required',
        'author' => 'required',
        'publisher' => 'required',
        'image' => 'nullable|image|max:2048',
    ]);
    
    $bookFind->title = $validateData['title'];
    $bookFind->categories_id = $validateData['categories_id'];
    $bookFind->author = $validateData['author'];
    $bookFind->publisher = $validateData['publisher'];

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images', $imageName);
        $bookFind->image = $imageName;
    }

    $bookFind->save();

    return redirect('/books')->with('successEdit', 'Book has been edited!');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function destroy(Books $books, Request $request)
    {
        $book = Books::find($request->id);
    
        // hapus file gambar dari storage jika ada
        if (!is_null($book->image)) {
            Storage::delete('public/images/' . $book->image);
        }
    
        $book->delete();
    
        return redirect('/books')->with('successDelete', 'Book has been deleted!');
    }
    
    
}
