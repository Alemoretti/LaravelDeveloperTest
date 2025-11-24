<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
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
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'birth_year',
        'gender',
        'homeworld_id',
    ];

    /**
     * Get the homeworld planet of the character.
     *
     * @return BelongsTo
     */
    public function homeworld(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'homeworld_id');
    }
}
