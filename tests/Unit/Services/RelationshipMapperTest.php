<?php

namespace Tests\Unit\Services;

use App\DataTransferObjects\CharacterDto;
use App\Models\Character;
use App\Models\Planet;
use App\Services\RelationshipMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipMapperTest extends TestCase
{
    use RefreshDatabase;

    private RelationshipMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new RelationshipMapper;
    }

    public function test_extract_swapi_id_from_valid_url(): void
    {
        $url = 'https://swapi.dev/api/planets/1/';
        $result = $this->mapper->extractSwapiId($url);

        $this->assertEquals('1', $result);
    }

    public function test_extract_swapi_id_from_different_resource(): void
    {
        $url = 'https://swapi.dev/api/people/42/';
        $result = $this->mapper->extractSwapiId($url);

        $this->assertEquals('42', $result);
    }

    public function test_extract_swapi_id_returns_null_for_invalid_url(): void
    {
        $url = 'https://swapi.dev/api/planets/invalid';
        $result = $this->mapper->extractSwapiId($url);

        $this->assertNull($result);
    }

    public function test_extract_swapi_id_returns_null_for_empty_url(): void
    {
        $result = $this->mapper->extractSwapiId('');

        $this->assertNull($result);
    }

    public function test_extract_swapi_id_returns_null_for_unknown(): void
    {
        $result = $this->mapper->extractSwapiId('unknown');

        $this->assertNull($result);
    }

    public function test_map_character_homeworld_associates_planet(): void
    {
        $planet = Planet::factory()->create(['swapi_id' => '1']);
        $character = Character::factory()->withoutHomeworld()->create();

        $dto = new CharacterDto(
            swapiId: '1',
            name: 'Test Character',
            height: null,
            mass: null,
            hairColor: null,
            skinColor: null,
            eyeColor: null,
            birthYear: null,
            gender: null,
            homeworldUrl: 'https://swapi.dev/api/planets/1/'
        );

        $this->mapper->mapCharacterHomeworld($character, $dto);
        $character->refresh();

        $this->assertEquals($planet->id, $character->homeworld_id);
    }

    public function test_map_character_homeworld_dissociates_when_planet_not_found(): void
    {
        $planet = Planet::factory()->create();
        $character = Character::factory()->create(['homeworld_id' => $planet->id]);

        $dto = new CharacterDto(
            swapiId: '1',
            name: 'Test Character',
            height: null,
            mass: null,
            hairColor: null,
            skinColor: null,
            eyeColor: null,
            birthYear: null,
            gender: null,
            homeworldUrl: 'https://swapi.dev/api/planets/999/'
        );

        $this->mapper->mapCharacterHomeworld($character, $dto);
        $character->refresh();

        $this->assertNull($character->homeworld_id);
    }
}
