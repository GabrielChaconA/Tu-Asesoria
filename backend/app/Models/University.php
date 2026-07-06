<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institutional_domain',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_universities', 'university_id', 'user_id')
                    ->withPivot(['is_primary', 'relationship_type']);
    }
}
