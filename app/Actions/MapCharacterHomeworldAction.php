<?php

namespace App\Actions;

use App\Contracts\PlanetRepositoryInterface;
use App\DataTransferObjects\CharacterDto;
use App\Models\Character;
use App\ValueObjects\SwapiId;
use Illuminate\Support\Facades\Log;

class MapCharacterHomeworldAction
{
    public function __construct(
        protected PlanetRepositoryInterface $planetRepository
    ) {}

    /**
     * Execute the action to map a character's homeworld.
     */
    public function execute(Character $character, CharacterDto $dto): void
    {
        if (empty($dto->homeworldUrl)) {
            Log::warning('Character has no homeworld URL', [
                'character_id' => $character->id,
                'character_name' => $character->name,
            ]);

            return;
        }

        $homeworldSwapiId = SwapiId::fromUrlOrNull($dto->homeworldUrl);

        if (! $homeworldSwapiId) {
            Log::warning('Could not extract homeworld SWAPI ID', [
                'character_id' => $character->id,
                'homeworld_url' => $dto->homeworldUrl,
            ]);

            return;
        }

        // Find the planet by swapi_id
        $planet = $this->planetRepository->findBySwapiId($homeworldSwapiId->value);

        if (! $planet) {
            Log::warning('Homeworld planet not found in database', [
                'character_id' => $character->id,
                'character_name' => $character->name,
                'homeworld_swapi_id' => $homeworldSwapiId->value,
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
}
