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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // kos, catering, laundry
            $table->integer('price');
            $table->string('image');
            
            // Spesifik Kos
            $table->string('gender')->nullable(); 
            $table->string('location')->nullable();
            $table->string('distance')->nullable();
            $table->boolean('is_verified')->default(false);
            
            // Spesifik Katering/Laundry
            $table->string('subtitle')->nullable();
            $table->string('frequency')->nullable();
            $table->string('schedule')->nullable();
            
            // Data Dinamis
            $table->float('rating')->default(0);
            $table->integer('reviews_count')->default(0);
            $table->json('features')->nullable(); // Tags kos / Benefits katering
            $table->json('extra_info')->nullable(); // Menu samples / Services laundry
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
