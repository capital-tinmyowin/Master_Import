<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImportLog extends Model
{
    protected $table = 'Item_ImportLog';
    protected $primaryKey = 'ImportLog_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'Import_Type',
        'Record_Count',
        'Error_Count',
        'Imported_By',
        'Imported_Date'
    ];
    
    protected $casts = [
        'Import_Type' => 'integer',
        'Record_Count' => 'integer',
        'Error_Count' => 'integer',
        'Imported_Date' => 'datetime'
    ];
    
    // Constants for Import_Type
    const IMPORT_TYPE_MASTER = 1;
    const IMPORT_TYPE_SKU = 2;
    
    // Relationships
    public function dataLogs()
    {
        return $this->hasMany(ItemImportDataLog::class, 'ImportLog_ID', 'ImportLog_ID');
    }
    
    public function errorLogs()
    {
        return $this->hasMany(ItemImportErrorLog::class, 'ImportLog_ID', 'ImportLog_ID');
    }
}