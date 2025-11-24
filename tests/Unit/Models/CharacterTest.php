<?php

namespace Tests\Unit\Models;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CharacterTest extends TestCase
{
    use RefreshDatabase;

    public function test_character_has_fillable_attributes(): void
    {
        $character = new Character;

        $this->assertEquals([
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
        ], $character->getFillable());
    }

    public function test_character_belongs_to_homeworld(): void
    {
        $planet = Planet::factory()->create();
        $character = Character::factory()->create(['homeworld_id' => $planet->id]);

        $this->assertInstanceOf(Planet::class, $character->homeworld);
        $this->assertEquals($planet->id, $character->homeworld->id);
    }

    public function test_character_can_exist_without_homeworld(): void
    {
        $character = Character::factory()->withoutHomeworld()->create();

        $this->assertNull($character->homeworld_id);
        $this->assertNull($character->homeworld);
    }

    public function test_character_has_timestamps(): void
    {
        $character = Character::factory()->create();

        $this->assertNotNull($character->created_at);
        $this->assertNotNull($character->updated_at);
    }
}
