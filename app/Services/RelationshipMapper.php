<?php

namespace App\Services;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Support\Facades\Log;

class RelationshipMapper
{
    /**
     * Map a character's homeworld relationship.
     *
     * @param  Character  $character  The character model
     * @param  array  $swapiData  The character data from SWAPI
     */
    public function mapCharacterHomeworld(Character $character, array $swapiData): void
    {
        if (empty($swapiData['homeworld'])) {
            Log::warning('Character has no homeworld URL', [
                'character_id' => $character->id,
                'character_name' => $character->name,
            ]);

            return;
        }

        $homeworldSwapiId = $this->extractSwapiId($swapiData['homeworld']);

        if (! $homeworldSwapiId) {
            Log::warning('Could not extract homeworld SWAPI ID', [
                'character_id' => $character->id,
                'homeworld_url' => $swapiData['homeworld'],
            ]);

            return;
        }

        // Find the planet by swapi_id
        $planet = Planet::where('swapi_id', $homeworldSwapiId)->first();

        if (! $planet) {
            Log::warning('Homeworld planet not found in database', [
                'character_id' => $character->id,
                'character_name' => $character->name,
                'homeworld_swapi_id' => $homeworldSwapiId,
            ]);

            // Set homeworld_id to null if planet doesn't exist yet
            $character->homeworld_id = null;
            $character->save();

            return;
        }

        // Link character to planet
        $character->homeworld_id = $planet->id;
        $character->save();

        Log::info('Character homeworld mapped', [
            'character_id' => $character->id,
            'character_name' => $character->name,
            'planet_id' => $planet->id,
            'planet_name' => $planet->name,
        ]);
    }

    /**
     * Extract SWAPI ID from a URL.
     *
     * @param  string|null  $url  The SWAPI URL
     * @return string|null The extracted ID or null if extraction fails
     */
    public function extractSwapiId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Extract ID from URL like "https://swapi.dev/api/planets/1/"
        $parts = explode('/', rtrim($url, '/'));
        $id = end($parts);

        return is_numeric($id) ? $id : null;
    }
}
