<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'region';
    
    protected $fillable = [
        'name',
        'url',
        'active',
        'latitude',
        'longitude',
        'cID',
        'zoneID',
        'educaCod'
    ];

    protected $casts = [
        'active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Get the cities for the region
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'region_id');
    }

    /**
     * Get the schools for the region
     */
    public function schools()
    {
        return $this->hasMany(School::class, 'region_id');
    }

    /**
     * Get the zone for the region
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zoneID');
    }
} 