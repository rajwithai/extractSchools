<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $table = 'director';
    
    protected $fillable = [
        'name',
        'accessemail',
        'email',
        'phone',
        'password',
        'active',
        'school_id'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get the school that owns the director
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
} 