<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Book([
            'Title'          => $row['title'],
            'Author'         => $row['author'],
            'ISBN'           => $row['isbn'],
            'PublishedDate'  => $row['publisheddate'],
            'Genre'          => $row['genre'],
            'NumberOfCopies' => $row['numberofcopies'],
            'IsAvailable'    => $row['isavailable'] ?? 1,
        ]);
    }
}
