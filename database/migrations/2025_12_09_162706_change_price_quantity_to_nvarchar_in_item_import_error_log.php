<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns exist before modifying
        if (Schema::hasTable('Item_Import_ErrorLog')) {
            Schema::table('Item_Import_ErrorLog', function (Blueprint $table) {
                // Change ListPrice from decimal to nvarchar
                if (Schema::hasColumn('Item_Import_ErrorLog', 'ListPrice')) {
                    $table->string('ListPrice', 100)->nullable()->change();
                }
                
                // Change SalePrice from decimal to nvarchar
                if (Schema::hasColumn('Item_Import_ErrorLog', 'SalePrice')) {
                    $table->string('SalePrice', 100)->nullable()->change();
                }
                
                // Change Quantity from integer to nvarchar
                if (Schema::hasColumn('Item_Import_ErrorLog', 'Quantity')) {
                    $table->string('Quantity', 100)->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('Item_Import_ErrorLog')) {
            Schema::table('Item_Import_ErrorLog', function (Blueprint $table) {
                // Revert back to original data types
                if (Schema::hasColumn('Item_Import_ErrorLog', 'ListPrice')) {
                    $table->decimal('ListPrice', 10, 4)->nullable()->change();
                }
                
                if (Schema::hasColumn('Item_Import_ErrorLog', 'SalePrice')) {
                    $table->decimal('SalePrice', 10, 4)->nullable()->change();
                }
                
                if (Schema::hasColumn('Item_Import_ErrorLog', 'Quantity')) {
                    $table->integer('Quantity')->nullable()->change();
                }
            });
        }
    }
};