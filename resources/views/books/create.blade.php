<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
</head>
<body>
<h1>Add Book</h1>
<form action="{{ route('books.store') }}" method="POST">
    @csrf
    <p>Title: <input type="text" name="Title" required></p>
    <p>Author: <input type="text" name="Author" required></p>
    <p>ISBN: <input type="text" name="ISBN"></p>
    <p>Published Date: <input type="date" name="PublishedDate" required></p>
    <p>Genre: <input type="text" name="Genre"></p>
    <p>Number of Copies: <input type="number" name="NumberOfCopies"></p>
    <p>Is Available:
        <select name="IsAvailable">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </p>
    <button type="submit">Save</button>
</form>
</body>
</html>
