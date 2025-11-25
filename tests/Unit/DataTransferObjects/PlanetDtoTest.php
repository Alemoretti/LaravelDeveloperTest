<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\PlanetDto;
use InvalidArgumentException;
use Tests\TestCase;

class PlanetDtoTest extends TestCase
{
    public function test_creates_dto_from_valid_array(): void
    {
        $data = [
            'url' => 'https://swapi.dev/api/planets/1/',
            'name' => 'Tatooine',
            'rotation_period' => '23',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000',
        ];

        $dto = PlanetDto::fromArray($data);

        $this->assertEquals('1', $dto->swapiId);
        $this->assertEquals('Tatooine', $dto->name);
        $this->assertEquals('23', $dto->rotationPeriod);
        $this->assertEquals('304', $dto->orbitalPeriod);
        $this->assertEquals('10465', $dto->diameter);
        $this->assertEquals('arid', $dto->climate);
        $this->assertEquals('1 standard', $dto->gravity);
        $this->assertEquals('desert', $dto->terrain);
        $this->assertEquals('1', $dto->surfaceWater);
        $this->assertEquals('200000', $dto->population);
    }

    public function test_creates_dto_with_nullable_fields(): void
    {
        $data = [
            'url' => 'https://swapi.dev/api/planets/1/',
            'name' => 'Test Planet',
        ];

        $dto = PlanetDto::fromArray($data);

        $this->assertEquals('1', $dto->swapiId);
        $this->assertEquals('Test Planet', $dto->name);
        $this->assertNull($dto->rotationPeriod);
        $this->assertNull($dto->orbitalPeriod);
        $this->assertNull($dto->diameter);
        $this->assertNull($dto->climate);
        $this->assertNull($dto->gravity);
        $this->assertNull($dto->terrain);
        $this->assertNull($dto->surfaceWater);
        $this->assertNull($dto->population);
    }

    public function test_throws_exception_for_missing_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Planet data must contain a valid URL');

        PlanetDto::fromArray(['name' => 'Test']);
    }

    public function test_throws_exception_for_missing_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Planet data must contain a valid name');

        PlanetDto::fromArray(['url' => 'https://swapi.dev/api/planets/1/']);
    }

    public function test_throws_exception_for_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Planet data must contain a valid name');

        PlanetDto::fromArray([
            'url' => 'https://swapi.dev/api/planets/1/',
            'name' => '',
        ]);
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $dto = new PlanetDto(
            swapiId: '1',
            name: 'Tatooine',
            rotationPeriod: '23',
            orbitalPeriod: '304',
            diameter: '10465',
            climate: 'arid',
            gravity: '1 standard',
            terrain: 'desert',
            surfaceWater: '1',
            population: '200000'
        );

        $array = $dto->toArray();

        $this->assertEquals([
            'swapi_id' => '1',
            'name' => 'Tatooine',
            'rotation_period' => '23',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000',
        ], $array);
    }

    public function test_extracts_swapi_id_correctly(): void
    {
        $data = [
            'url' => 'https://swapi.dev/api/planets/42/',
            'name' => 'Test Planet',
        ];

        $dto = PlanetDto::fromArray($data);

        $this->assertEquals('42', $dto->swapiId);
    }
}
