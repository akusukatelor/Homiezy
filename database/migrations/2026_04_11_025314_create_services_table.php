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
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('type'); // kos, katering, laundry
        $table->integer('price');
        $table->string('image');
        $table->string('whatsapp');
        $table->string('gender')->nullable(); 
        $table->string('location')->nullable();
        $table->string('distance')->default('Baru Terdaftar');
        $table->boolean('is_verified')->default(false);
        $table->string('subtitle')->nullable();
        $table->string('frequency')->nullable(); 
        $table->string('schedule')->nullable();
        
        // Data List Dinamis
        $table->json('features')->nullable(); 
        $table->json('extra_info')->nullable(); 
        
        $table->float('rating')->default(5.0);
        $table->integer('reviews_count')->default(0);
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
