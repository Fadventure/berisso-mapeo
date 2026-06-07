<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('email_lugar')->nullable()->after('website');
        });
    }
    protected $fillable = [
        'name', 
        'category_id', 
        'description', 
        'address', 
        'hours', 
        'phone', 
        'website', 
        'image',
        'email_lugar',  // Agregar esta línea
        'user_id', 
        'slug', 
        'published'
    ];
};
