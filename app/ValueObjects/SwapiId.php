<?php

namespace App\ValueObjects;

use InvalidArgumentException;
use Stringable;

readonly class SwapiId implements Stringable
{
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('SWAPI ID cannot be empty');
        }

        if (! is_numeric($value)) {
            throw new InvalidArgumentException("SWAPI ID must be numeric, got: {$value}");
        }
    }

    /**
     * Create from a SWAPI URL.
     */
    public static function fromUrl(string $url): self
    {
        if (empty($url)) {
            throw new InvalidArgumentException('URL cannot be empty');
        }

        // Extract ID from URL like "https://swapi.dev/api/people/1/"
        $parts = explode('/', rtrim($url, '/'));
        $id = end($parts);

        return new self($id);
    }

    /**
     * Create from a nullable URL, returning null if URL is null/empty.
     */
    public static function fromUrlOrNull(?string $url): ?self
    {
        if (empty($url)) {
            return null;
        }

        try {
            return self::fromUrl($url);
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Check equality with another SwapiId.
     */
    public function equals(?self $other): bool
    {
        if ($other === null) {
            return false;
        }

        return $this->value === $other->value;
    }
}
