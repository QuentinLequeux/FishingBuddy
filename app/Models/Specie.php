<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specie extends Model
{
    use HasFactory;

    public function spots()
    {
        return $this->belongsToMany(Spot::class, 'spot_species');
    }
}
