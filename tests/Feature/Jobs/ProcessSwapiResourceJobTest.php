<?php

namespace Tests\Feature\Jobs;

use App\Actions\MapCharacterHomeworldAction;
use App\Actions\SyncCharacterAction;
use App\Actions\SyncPlanetAction;
use App\Enums\SyncStatus;
use App\Jobs\ProcessSwapiResourceJob;
use App\Models\Planet;
use App\Models\SyncLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessSwapiResourceJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_processes_planet_successfully(): void
    {
        $planetData = [
            'name' => 'Tatooine',
            'rotation_period' => '23',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000',
            'url' => 'https://swapi.dev/api/planets/1/',
        ];

        $job = new ProcessSwapiResourceJob('planets', $planetData);
        $job->handle(
            app(SyncCharacterAction::class),
            app(SyncPlanetAction::class),
            app(MapCharacterHomeworldAction::class)
        );

        $this->assertDatabaseHas('planets', [
            'swapi_id' => '1',
            'name' => 'Tatooine',
            'climate' => 'arid',
        ]);

        $this->assertDatabaseHas('sync_logs', [
            'resource_type' => 'planets',
            'resource_id' => '1',
            'status' => SyncStatus::SUCCESS->value,
        ]);
    }

    public function test_job_processes_character_successfully(): void
    {
        $planet = Planet::factory()->create(['swapi_id' => '1']);

        $characterData = [
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'homeworld' => 'https://swapi.dev/api/planets/1/',
            'url' => 'https://swapi.dev/api/people/1/',
        ];

        $job = new ProcessSwapiResourceJob('people', $characterData);
        $job->handle(
            app(SyncCharacterAction::class),
            app(SyncPlanetAction::class),
            app(MapCharacterHomeworldAction::class)
        );

        $this->assertDatabaseHas('characters', [
            'swapi_id' => '1',
            'name' => 'Luke Skywalker',
            'homeworld_id' => $planet->id,
        ]);

        $this->assertDatabaseHas('sync_logs', [
            'resource_type' => 'people',
            'resource_id' => '1',
            'status' => SyncStatus::SUCCESS->value,
        ]);
    }

    public function test_job_is_idempotent_for_already_synced_resource(): void
    {
        $planetData = [
            'name' => 'Tatooine',
            'rotation_period' => '23',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000',
            'url' => 'https://swapi.dev/api/planets/1/',
        ];

        SyncLog::create([
            'resource_type' => 'planets',
            'resource_id' => '1',
            'status' => SyncStatus::SUCCESS,
            'synced_at' => now(),
        ]);

        $job = new ProcessSwapiResourceJob('planets', $planetData);
        $job->handle(
            app(SyncCharacterAction::class),
            app(SyncPlanetAction::class),
            app(MapCharacterHomeworldAction::class)
        );

        $this->assertDatabaseMissing('planets', [
            'swapi_id' => '1',
        ]);
    }

    public function test_job_updates_existing_resource(): void
    {
        $planet = Planet::factory()->create([
            'swapi_id' => '1',
            'name' => 'Old Name',
            'climate' => 'old climate',
        ]);

        $planetData = [
            'name' => 'Tatooine',
            'rotation_period' => '23',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000',
            'url' => 'https://swapi.dev/api/planets/1/',
        ];

        $job = new ProcessSwapiResourceJob('planets', $planetData);
        $job->handle(
            app(SyncCharacterAction::class),
            app(SyncPlanetAction::class),
            app(MapCharacterHomeworldAction::class)
        );

        $planet->refresh();
        $this->assertEquals('Tatooine', $planet->name);
        $this->assertEquals('arid', $planet->climate);
    }
}
