<?php

namespace App\Contracts;

use App\DataTransferObjects\CharacterDto;
use App\Models\Character;

interface RelationshipMapperInterface
{
    /**
     * Map a character's homeworld relationship.
     */
    public function mapCharacterHomeworld(Character $character, CharacterDto $dto): void;

    /**
     * Extract SWAPI ID from a URL.
     */
    public function extractSwapiId(?string $url): ?string;
}
