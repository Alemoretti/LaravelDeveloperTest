<?php

namespace Tests\Unit\Models;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanetTest extends TestCase
{
    use RefreshDatabase;

    public function test_planet_has_fillable_attributes(): void
    {
        $planet = new Planet;

        $this->assertEquals([
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
        ], $planet->getFillable());
    }

    public function test_planet_has_many_characters(): void
    {
        $planet = Planet::factory()->create();
        $character1 = Character::factory()->create(['homeworld_id' => $planet->id]);
        $character2 = Character::factory()->create(['homeworld_id' => $planet->id]);

        $this->assertCount(2, $planet->characters);
        $this->assertTrue($planet->characters->contains($character1));
        $this->assertTrue($planet->characters->contains($character2));
    }

    public function test_planet_residents_relationship_works(): void
    {
        $planet = Planet::factory()->create();
        $character = Character::factory()->create(['homeworld_id' => $planet->id]);

        $this->assertCount(1, $planet->residents);
        $this->assertEquals($character->id, $planet->residents->first()->id);
    }

    public function test_planet_has_timestamps(): void
    {
        $planet = Planet::factory()->create();

        $this->assertNotNull($planet->created_at);
        $this->assertNotNull($planet->updated_at);
    }
}
