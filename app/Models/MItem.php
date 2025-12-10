<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MItem extends Model
{
    use HasFactory;

    protected $table = 'M_Item';
    protected $primaryKey = 'ID';
    public $incrementing = true; // auto-increment enabled
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'Item_Code',
        'ItemName',
        'JanCD',
        'MakerName',
        'Memo',
        'ListPrice',
        'SalePrice',
        'CreatedDate',
        'UpdatedDate',
        'Createdby',
        'Updatedby'
    ];

     public function images()
    {
        return $this->hasMany(\App\Models\MItemImage::class, 'Item_Code', 'Item_Code');
    }
}
