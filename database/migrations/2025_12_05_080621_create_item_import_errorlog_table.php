<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('Item_Import_ErrorLog', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('ImportLog_ID');
            $table->string('Item_Code', 50)->nullable();
            $table->string('Item_Name', 200)->nullable();
            $table->string('JanCD', 13)->nullable();
            $table->string('MakerName', 200)->nullable();
            $table->text('Memo')->nullable();
            $table->decimal('ListPrice', 19, 4)->nullable();
            $table->decimal('SalePrice', 19, 4)->nullable();
            $table->string('Size_Name', 100)->nullable();
            $table->string('Color_Name', 100)->nullable();
            $table->string('Size_Code', 50)->nullable();
            $table->string('Color_Code', 50)->nullable();
            $table->string('JanCode', 13)->nullable();
            $table->integer('Quantity')->nullable();
            $table->text('Error_Msg');
            
            // Foreign key
            $table->foreign('ImportLog_ID')->references('ImportLog_ID')->on('Item_ImportLog')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Item_Import_ErrorLog');
    }
};