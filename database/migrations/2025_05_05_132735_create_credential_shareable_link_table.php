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
        Schema::create('credential_shareable_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credentials_id')->constrained()->onDelete('cascade');
            $table->foreignId('shareable_credential_link_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Make the combination unique
            $table->unique(['credentials_id', 'shareable_credential_link_id'], 'credential_link_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credential_shareable_link');
    }
};
