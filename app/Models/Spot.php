<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spot extends Model
{
    use HasFactory;

    protected $casts = [
        'environement' => 'array',
        'rules' => 'array',
        'equipments' => 'array',
    ];

    public function species()
    {
        return $this->belongsToMany(Specie::class, 'spot_species');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
