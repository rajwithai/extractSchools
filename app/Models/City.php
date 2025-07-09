<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';
    
    protected $fillable = [
        'name',
        'url',
        'active',
        'latitude',
        'longitude',
        'region_id',
        'codigo_ine',
        'ineID'
    ];

    protected $casts = [
        'active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Get the region that owns the city
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Get the schools for the city
     */
    public function schools()
    {
        return $this->hasMany(School::class, 'city_id');
    }
} 