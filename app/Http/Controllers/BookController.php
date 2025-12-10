<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;

class BookController extends Controller
{
    // Show all books
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    // Show create form
    public function create()
    {
        return view('books.create');
    }

    // Store new book
    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:100',
            'Author' => 'required|string|max:100',
            'ISBN' => 'nullable|string|max:13',
            'PublishedDate' => 'required|date',
            'Genre' => 'nullable|string|max:50',
            'NumberOfCopies' => 'nullable|integer',
            'IsAvailable' => 'required|boolean'
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    // Show edit form
    public function edit($BookId)
    {
        $book = Book::findOrFail($BookId);
        return view('books.edit', compact('book'));
    }

    // Update existing book
    public function update(Request $request, $BookId)
    {
        $request->validate([
            'Title' => 'required|string|max:100',
            'Author' => 'required|string|max:100',
            'ISBN' => 'nullable|string|max:13',
            'PublishedDate' => 'required|date',
            'Genre' => 'nullable|string|max:50',
            'NumberOfCopies' => 'nullable|integer',
            'IsAvailable' => 'required|boolean'
        ]);

        $book = Book::findOrFail($BookId);
        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    // Delete a book
    public function destroy($BookId)
    {
        Book::destroy($BookId);
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    public function importForm()
{
    return view('books.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new BooksImport, $request->file('file'));

    return redirect()->route('books.index')->with('success', 'Books imported successfully!');
}
}
