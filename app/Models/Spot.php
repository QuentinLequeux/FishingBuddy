<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spot extends Model
{
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'name',
        'license',
        'url',
        'environement',
        'rules',
        'equipments',
        'is_public',
    ];

    protected $casts = [
        'environement' => 'array',
        'rules' => 'array',
        'equipments' => 'array',
    ];

    public function species()
    {
        return $this->belongsToMany(Specie::class, 'spot_species', 'spot_id', 'specie_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
