<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('Item_ImportLog', function (Blueprint $table) {
            $table->id('ImportLog_ID');
            $table->tinyInteger('Import_Type')->default(1); // 1=Master, 2=SKU
            $table->integer('Record_Count')->default(0);
            $table->integer('Error_Count')->default(0);
            $table->string('Imported_By', 200);
            $table->timestamp('Imported_Date')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Item_ImportLog');
    }
};