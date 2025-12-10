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
        // Check if the column exists before modifying
        if (Schema::hasColumn('Item_Import_ErrorLog', 'JanCD')) {
            Schema::table('Item_Import_ErrorLog', function (Blueprint $table) {
                // Change JanCD column length (example: change to VARCHAR(50))
                $table->string('JanCD', 50)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('Item_Import_ErrorLog', 'JanCD')) {
            Schema::table('Item_Import_ErrorLog', function (Blueprint $table) {
                // Revert back to original length (example: VARCHAR(20))
                $table->string('JanCD', 20)->nullable()->change();
            });
        }
    }
};