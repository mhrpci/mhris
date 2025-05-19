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
        Schema::table('credentials', function (Blueprint $table) {
            // First drop the existing foreign key constraint
            $table->dropForeign(['employee_id']);
            
            // Then recreate it with cascade delete
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credentials', function (Blueprint $table) {
            // Drop the cascade delete foreign key
            $table->dropForeign(['employee_id']);
            
            // Recreate the original foreign key without cascade delete
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees');
        });
    }
};
