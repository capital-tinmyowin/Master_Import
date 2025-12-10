<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImportErrorLog extends Model
{
    protected $table = 'Item_Import_ErrorLog';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    protected $fillable = [
        'ImportLog_ID',
        'Item_Code',
        'Item_Name',
        'JanCD',
        'MakerName',
        'Memo',
        'ListPrice',
        'SalePrice',
        'Size_Name',
        'Color_Name',
        'Size_Code',
        'Color_Code',
        'JanCode',
        'Quantity',
        'Error_Msg'
    ];
    
}