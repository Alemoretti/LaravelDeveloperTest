<?php

namespace App\DataTransferObjects;

use InvalidArgumentException;

class CharacterDto
{
    public function __construct(
        public readonly string $swapiId,
        public readonly string $name,
        public readonly ?string $height,
        public readonly ?string $mass,
        public readonly ?string $hairColor,
        public readonly ?string $skinColor,
        public readonly ?string $eyeColor,
        public readonly ?string $birthYear,
        public readonly ?string $gender,
        public readonly ?string $homeworldUrl,
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
            height: $data['height'] ?? null,
            mass: $data['mass'] ?? null,
            hairColor: $data['hair_color'] ?? null,
            skinColor: $data['skin_color'] ?? null,
            eyeColor: $data['eye_color'] ?? null,
            birthYear: $data['birth_year'] ?? null,
            gender: $data['gender'] ?? null,
            homeworldUrl: $data['homeworld'] ?? null,
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
            throw new InvalidArgumentException('Character data must contain a valid URL');
        }

        if (! isset($data['name']) || ! is_string($data['name']) || empty($data['name'])) {
            throw new InvalidArgumentException('Character data must contain a valid name');
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
        // Extract ID from URL like "https://swapi.dev/api/people/1/"
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
            'height' => $this->height,
            'mass' => $this->mass,
            'hair_color' => $this->hairColor,
            'skin_color' => $this->skinColor,
            'eye_color' => $this->eyeColor,
            'birth_year' => $this->birthYear,
            'gender' => $this->gender,
        ];
    }
}
