<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'swapi_id',
        'name',
        'rotation_period',
        'orbital_period',
        'diameter',
        'climate',
        'gravity',
        'terrain',
        'surface_water',
        'population',
    ];

    /**
     * Get the characters that belong to this planet (residents).
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'homeworld_id');
    }

    /**
     * Alias for characters relationship (residents).
     */
    public function residents(): HasMany
    {
        return $this->characters();
    }
}
