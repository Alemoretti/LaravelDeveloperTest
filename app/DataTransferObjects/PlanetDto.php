<?php

namespace App\DataTransferObjects;

use InvalidArgumentException;

class PlanetDto
{
    public function __construct(
        public readonly string $swapiId,
        public readonly string $name,
        public readonly ?string $rotationPeriod,
        public readonly ?string $orbitalPeriod,
        public readonly ?string $diameter,
        public readonly ?string $climate,
        public readonly ?string $gravity,
        public readonly ?string $terrain,
        public readonly ?string $surfaceWater,
        public readonly ?string $population,
    ) {}

    /**
     * Create a DTO from SWAPI API response data.
     *
     * @param  array  $data  The raw API response data
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        self::validate($data);

        $swapiId = self::extractSwapiId($data['url']);

        return new self(
            swapiId: $swapiId,
            name: $data['name'],
            rotationPeriod: $data['rotation_period'] ?? null,
            orbitalPeriod: $data['orbital_period'] ?? null,
            diameter: $data['diameter'] ?? null,
            climate: $data['climate'] ?? null,
            gravity: $data['gravity'] ?? null,
            terrain: $data['terrain'] ?? null,
            surfaceWater: $data['surface_water'] ?? null,
            population: $data['population'] ?? null,
        );
    }

    /**
     * Validate the API response data.
     *
     *
     * @throws InvalidArgumentException
     */
    private static function validate(array $data): void
    {
        if (! isset($data['url']) || ! is_string($data['url'])) {
            throw new InvalidArgumentException('Planet data must contain a valid URL');
        }

        if (! isset($data['name']) || ! is_string($data['name']) || empty($data['name'])) {
            throw new InvalidArgumentException('Planet data must contain a valid name');
        }
    }

    /**
     * Extract SWAPI ID from a URL.
     *
     * @param  string  $url  The SWAPI URL
     * @return string The extracted ID
     */
    private static function extractSwapiId(string $url): string
    {
        // Extract ID from URL like "https://swapi.dev/api/planets/1/"
        $parts = explode('/', rtrim($url, '/'));

        return end($parts);
    }

    /**
     * Convert DTO to array for database storage.
     */
    public function toArray(): array
    {
        return [
            'swapi_id' => $this->swapiId,
            'name' => $this->name,
            'rotation_period' => $this->rotationPeriod,
            'orbital_period' => $this->orbitalPeriod,
            'diameter' => $this->diameter,
            'climate' => $this->climate,
            'gravity' => $this->gravity,
            'terrain' => $this->terrain,
            'surface_water' => $this->surfaceWater,
            'population' => $this->population,
        ];
    }
}
