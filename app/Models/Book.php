<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'dbo.Book'; // exact table name in SQL Server
    protected $primaryKey = 'BookId';
    public $timestamps = false; // disable Laravel’s created_at / updated_at columns

    protected $fillable = [
        'Title',
        'Author',
        'ISBN',
        'PublishedDate',
        'Genre',
        'NumberOfCopies',
        'IsAvailable'
    ];

    protected $casts = [
        'PublishedDate' => 'datetime',
        'IsAvailable' => 'boolean'
    ];
}
