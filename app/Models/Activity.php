<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'user_id',
        'specie_id',
        'size',
        'weight',
        'lure_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'activity_id');
    }

    public function specie()
    {
        return $this->belongsTo(Specie::class);
    }

    public function lure()
    {
        return $this->belongsTo(Lure::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user');
    }
}
