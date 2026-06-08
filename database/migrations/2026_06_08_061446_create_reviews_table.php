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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('service_id')->constrained()->onDelete('cascade');
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->tinyInteger('rating'); // 1-5
        $table->text('comment');
        $table->timestamps();

        // Satu user hanya bisa review satu layanan satu kali
        $table->unique(['user_id', 'service_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('reviews');
}
};
