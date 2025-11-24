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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('swapi_id')->unique();
            $table->string('name');
            $table->string('height')->nullable();
            $table->string('mass')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('skin_color')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('birth_year')->nullable();
            $table->string('gender')->nullable();
            $table->foreignId('homeworld_id')->nullable()->constrained('planets')->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('homeworld_id');
            $table->index('swapi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
