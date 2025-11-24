<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_planets(): void
    {
        Planet::factory()->count(3)->create();

        $response = $this->get(route('planets.index'));

        $response->assertStatus(200);
        $response->assertViewIs('planets.index');
        $response->assertViewHas('planets');
    }

    public function test_index_can_search_planets_by_name(): void
    {
        Planet::factory()->create(['name' => 'Tatooine']);
        Planet::factory()->create(['name' => 'Alderaan']);

        $response = $this->get(route('planets.index', ['search' => 'Tatooine']));

        $response->assertStatus(200);
        $response->assertSee('Tatooine');
        $response->assertDontSee('Alderaan');
    }

    public function test_index_can_search_planets_by_climate(): void
    {
        Planet::factory()->create(['name' => 'Hoth', 'climate' => 'frozen']);
        Planet::factory()->create(['name' => 'Tatooine', 'climate' => 'arid']);

        $response = $this->get(route('planets.index', ['search' => 'frozen']));

        $response->assertStatus(200);
        $response->assertSee('Hoth');
        $response->assertDontSee('Tatooine');
    }

    public function test_index_can_filter_by_climate(): void
    {
        Planet::factory()->create(['name' => 'Hoth', 'climate' => 'frozen']);
        Planet::factory()->create(['name' => 'Tatooine', 'climate' => 'arid']);

        $response = $this->get(route('planets.index', ['climate' => 'arid']));

        $response->assertStatus(200);
        $response->assertSee('Tatooine');
        $response->assertDontSee('Hoth');
    }

    public function test_index_can_sort_planets(): void
    {
        Planet::factory()->create(['name' => 'Tatooine', 'diameter' => '10465']);
        Planet::factory()->create(['name' => 'Alderaan', 'diameter' => '12500']);

        $response = $this->get(route('planets.index', ['sort_by' => 'diameter', 'sort_order' => 'desc']));

        $response->assertStatus(200);
        $content = $response->getContent();
        $posAlderaan = strpos($content, 'Alderaan');
        $posTatooine = strpos($content, 'Tatooine');

        $this->assertNotFalse($posAlderaan);
        $this->assertNotFalse($posTatooine);
        $this->assertLessThan($posTatooine, $posAlderaan);
    }

    public function test_index_paginates_planets(): void
    {
        Planet::factory()->count(20)->create();

        $response = $this->get(route('planets.index'));

        $response->assertStatus(200);
        $response->assertViewHas('planets', function ($planets) {
            return $planets->count() === 15;
        });
    }

    public function test_index_includes_character_count(): void
    {
        $planet = Planet::factory()->create(['name' => 'Tatooine']);
        Character::factory()->count(3)->create(['homeworld_id' => $planet->id]);

        $response = $this->get(route('planets.index'));

        $response->assertStatus(200);
        $response->assertSee('3 residents');
    }

    public function test_show_displays_planet_details(): void
    {
        $planet = Planet::factory()->create([
            'name' => 'Tatooine',
            'climate' => 'arid',
            'terrain' => 'desert',
            'population' => 200000,
        ]);

        $response = $this->get(route('planets.show', $planet));

        $response->assertStatus(200);
        $response->assertViewIs('planets.show');
        $response->assertViewHas('planet');
        $response->assertSee('Tatooine');
        $response->assertSee('Arid');
        $response->assertSee('Desert');
    }

    public function test_show_displays_planet_residents(): void
    {
        $planet = Planet::factory()->create(['name' => 'Tatooine']);
        $luke = Character::factory()->create(['name' => 'Luke Skywalker', 'homeworld_id' => $planet->id]);
        $anakin = Character::factory()->create(['name' => 'Anakin Skywalker', 'homeworld_id' => $planet->id]);

        $response = $this->get(route('planets.show', $planet));

        $response->assertStatus(200);
        $response->assertSee('Luke Skywalker');
        $response->assertSee('Anakin Skywalker');
    }

    public function test_show_paginates_residents(): void
    {
        $planet = Planet::factory()->create(['name' => 'Coruscant']);
        Character::factory()->count(15)->create(['homeworld_id' => $planet->id]);

        $response = $this->get(route('planets.show', $planet));

        $response->assertStatus(200);
        $response->assertViewHas('residents', function ($residents) {
            return $residents->count() === 10;
        });
    }

    public function test_show_handles_planet_without_residents(): void
    {
        $planet = Planet::factory()->create(['name' => 'Empty Planet']);

        $response = $this->get(route('planets.show', $planet));

        $response->assertStatus(200);
        $response->assertSee('Empty Planet');
        $response->assertSee('No known residents');
    }
}

