<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'address',
        'hours',
        'phone',
        'website',
        'email_lugar',
        'image', // imagen principal
        'user_id',
        'published'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Nueva relación para imágenes múltiples
    public function images(): HasMany
    {
        return $this->hasMany(BusinessImage::class)->orderBy('order');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}