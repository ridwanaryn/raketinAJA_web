<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'owner_id', 'name', 'sport_type', 'price_per_hour', 'capacity',
    'is_indoor', 'features', 'description', 'location', 'image_url', 'status'
])]
class Field extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_indoor' => 'boolean',
            'features' => 'array',
            'price_per_hour' => 'float',
            'capacity' => 'integer',
        ];
    }

    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        return [$value];
    }

    public function setImageUrlAttribute($value)
    {
        $this->attributes['image_url'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getFirstImageUrlAttribute()
    {
        $images = $this->image_url;
        return is_array($images) ? ($images[0] ?? 'https://images.unsplash.com/photo-1545809074-59472b3f5eca') : $images;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'field_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'field_id');
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 0.0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySport($query, $sport)
    {
        if (empty($sport)) {
            return $query;
        }
        return $query->where('sport_type', $sport);
    }
}
