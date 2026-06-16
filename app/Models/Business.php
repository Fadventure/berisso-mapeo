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
        'longitude',
        'latitude',
        'hours',
        'phone',
        'website',
        'email_lugar',
        'facebook',
        'instagram',
        'image', // imagen principal
        'user_id',
        'published'
        ,'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
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

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope para negocios aprobados
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')->where('published', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}