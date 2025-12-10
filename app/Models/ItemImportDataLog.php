<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImportDataLog extends Model
{
    protected $table = 'Item_Import_DataLog';
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
        'Quantity'
    ];

    // protected $casts = [
    //     'ListPrice' => 'decimal:4',
    //     'SalePrice' => 'decimal:4',
    //     'Quantity' => 'integer'
    // ];
}