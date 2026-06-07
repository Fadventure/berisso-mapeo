<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('image_url');
            $table->integer('order')->default(0); // Para ordenar las imágenes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_images');
    }
};