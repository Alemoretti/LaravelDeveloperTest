<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\CharacterDto;
use InvalidArgumentException;
use Tests\TestCase;

class CharacterDtoTest extends TestCase
{
    public function test_creates_dto_from_valid_array(): void
    {
        $data = [
            'url' => 'https://swapi.dev/api/people/1/',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'homeworld' => 'https://swapi.dev/api/planets/1/',
        ];

        $dto = CharacterDto::fromArray($data);

        $this->assertEquals('1', $dto->swapiId);
        $this->assertEquals('Luke Skywalker', $dto->name);
        $this->assertEquals('172', $dto->height);
        $this->assertEquals('77', $dto->mass);
        $this->assertEquals('blond', $dto->hairColor);
        $this->assertEquals('fair', $dto->skinColor);
        $this->assertEquals('blue', $dto->eyeColor);
        $this->assertEquals('19BBY', $dto->birthYear);
        $this->assertEquals('male', $dto->gender);
        $this->assertEquals('https://swapi.dev/api/planets/1/', $dto->homeworldUrl);
    }

    public function test_creates_dto_with_nullable_fields(): void
    {
        $data = [
            'url' => 'https://swapi.dev/api/people/1/',
            'name' => 'Test Character',
        ];

        $dto = CharacterDto::fromArray($data);

        $this->assertEquals('1', $dto->swapiId);
        $this->assertEquals('Test Character', $dto->name);
        $this->assertNull($dto->height);
        $this->assertNull($dto->mass);
        $this->assertNull($dto->hairColor);
        $this->assertNull($dto->skinColor);
        $this->assertNull($dto->eyeColor);
        $this->assertNull($dto->birthYear);
        $this->assertNull($dto->gender);
        $this->assertNull($dto->homeworldUrl);
    }

    public function test_throws_exception_for_missing_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Character data must contain a valid URL');

        CharacterDto::fromArray(['name' => 'Test']);
    }

    public function test_throws_exception_for_missing_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Character data must contain a valid name');

        CharacterDto::fromArray(['url' => 'https://swapi.dev/api/people/1/']);
    }

    public function test_throws_exception_for_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Character data must contain a valid name');

        CharacterDto::fromArray([
            'url' => 'https://swapi.dev/api/people/1/',
            'name' => '',
        ]);
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $dto = new CharacterDto(
            swapiId: '1',
            name: 'Luke Skywalker',
            height: '172',
            mass: '77',
            hairColor: 'blond',
            skinColor: 'fair',
            eyeColor: 'blue',
            birthYear: '19BBY',
            gender: 'male',
            homeworldUrl: 'https://swapi.dev/api/planets/1/'
        );

        $array = $dto->toArray();

        $this->assertEquals([
            'swapi_id' => '1',
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
        ], $array);

        // Note: homeworldUrl is not included in toArray() as it's handled separately
        $this->assertArrayNotHasKey('homeworld_url', $array);
    }

    public function test_extracts_swapi_id_correctly(): void
    {
        $data = [
            'url' => 'https://swapi.dev/api/people/42/',
            'name' => 'Test Character',
        ];

        $dto = CharacterDto::fromArray($data);

        $this->assertEquals('42', $dto->swapiId);
    }
}
