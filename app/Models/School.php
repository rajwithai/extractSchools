<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'school';
    
    protected $fillable = [
        'code',
        'name',
        'latitude',
        'longitude',
        'accessemail',
        'email',
        'email2',
        'phone',
        'website',
        'scope_id',
        'type_id',
        'religion_id',
        'religion_tipo_id',
        'price_id',
        'segregation_id',
        'region_id',
        'city_id',
        'address',
        'address_short',
        'postal',
        'hidden',
        'active',
        'total_students',
        'place_id',
        'director_id',
        'contact_id'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'hidden' => 'boolean',
        'active' => 'boolean',
        'total_students' => 'integer'
    ];

    /**
     * Get the region that owns the school
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Get the city that owns the school
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the director for the school
     */
    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }

    /**
     * Get the services for the school
     */
    public function services()
    {
        return $this->hasMany(SchoolService::class, 'school_id');
    }

    /**
     * Get the educations for the school
     */
    public function educations()
    {
        return $this->hasMany(SchoolEducation::class, 'school_id');
    }
} 