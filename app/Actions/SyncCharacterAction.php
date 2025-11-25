<?php

namespace App\Actions;

use App\Contracts\CharacterRepositoryInterface;
use App\DataTransferObjects\CharacterDto;
use App\Enums\SyncStatus;
use App\Models\Character;
use App\Models\SyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncCharacterAction
{
    public function __construct(
        protected CharacterRepositoryInterface $characterRepository
    ) {}

    /**
     * Execute the action to sync a character.
     */
    public function execute(CharacterDto $dto): Character
    {
        return DB::transaction(function () use ($dto) {
            $character = $this->characterRepository->updateOrCreate(
                $dto->swapiId,
                $dto->toArray()
            );

            // Log sync operation
            SyncLog::create([
                'resource_type' => 'people',
                'resource_id' => $dto->swapiId,
                'status' => SyncStatus::SUCCESS->value,
                'synced_at' => now(),
            ]);

            Log::info('Character synced', [
                'swapi_id' => $dto->swapiId,
                'name' => $character->name,
            ]);

            return $character;
        });
    }
}
