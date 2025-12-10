<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MSKU extends Model
{
    protected $table = 'M_SKU';
    public $timestamps = false;

    protected $fillable = [
        'Item_AdminCode',
        'Item_Code',
        'Size_Name',
        'Color_Name',
        'Size_Code',
        'Color_Code',
        'JanCD',
        'Quantity',
        'CreatedDate',
        'UpdatedDate',
        'Createdby',
        'Updatedby'
    ];
}
