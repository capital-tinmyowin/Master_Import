<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MItemImage extends Model
{
    protected $table = 'M_ItemImage'; 

    protected $primaryKey = 'ID';    

    public $timestamps = false;     

    protected $fillable = [
        'Item_Code',
        'Image_Name',
        'path',
        'CreatedDate',
        'UpdatedDate',
        'Createdby',
        'Updatedby',
    ];
}
