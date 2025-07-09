<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table = 'zone';
    
    protected $fillable = [
        'name',
        'url',
        'active',
        'educaCod'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get the regions for the zone
     */
    public function regions()
    {
        return $this->hasMany(Region::class, 'zoneID');
    }
} 