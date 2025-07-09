<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolService extends Model
{
    use HasFactory;

    protected $table = 'school_service';
    
    protected $fillable = [
        'school_id',
        'service_id',
        'data',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'data' => 'integer'
    ];

    /**
     * Get the school that owns the service
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
} 