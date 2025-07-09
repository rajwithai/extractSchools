<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEducation extends Model
{
    use HasFactory;

    protected $table = 'school_education';
    
    protected $fillable = [
        'school_id',
        'maingroup_id',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get the school that owns the education
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
} 