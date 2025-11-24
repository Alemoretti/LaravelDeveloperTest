<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CharacterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_characters(): void
    {
        $planet = Planet::factory()->create(['name' => 'Tatooine']);
        Character::factory()->count(3)->create(['homeworld_id' => $planet->id]);

        $response = $this->get(route('characters.index'));

        $response->assertStatus(200);
        $response->assertViewIs('characters.index');
        $response->assertViewHas('characters');
    }

    public function test_index_can_search_characters_by_name(): void
    {
        $character1 = Character::factory()->create(['name' => 'Luke Skywalker']);
        $character2 = Character::factory()->create(['name' => 'Darth Vader']);

        $response = $this->get(route('characters.index', ['search' => 'Luke']));

        $response->assertStatus(200);
        $response->assertSee('Luke Skywalker');
        $response->assertDontSee('Darth Vader');
    }

    public function test_index_can_filter_by_gender(): void
    {
        Character::factory()->create(['name' => 'Luke Skywalker', 'gender' => 'male']);
        Character::factory()->create(['name' => 'Leia Organa', 'gender' => 'female']);

        $response = $this->get(route('characters.index', ['gender' => 'female']));

        $response->assertStatus(200);
        $response->assertSee('Leia Organa');
        $response->assertDontSee('Luke Skywalker');
    }

    public function test_index_can_filter_by_homeworld(): void
    {
        $tatooine = Planet::factory()->create(['name' => 'Tatooine']);
        $alderaan = Planet::factory()->create(['name' => 'Alderaan']);

        $luke = Character::factory()->create(['name' => 'Luke Skywalker', 'homeworld_id' => $tatooine->id]);
        $leia = Character::factory()->create(['name' => 'Leia Organa', 'homeworld_id' => $alderaan->id]);

        $response = $this->get(route('characters.index', ['homeworld_id' => $tatooine->id]));

        $response->assertStatus(200);
        $response->assertSee('Luke Skywalker');
        $response->assertDontSee('Leia Organa');
    }

    public function test_index_can_sort_characters(): void
    {
        Character::factory()->create(['name' => 'Yoda', 'height' => '66']);
        Character::factory()->create(['name' => 'Chewbacca', 'height' => '228']);

        $response = $this->get(route('characters.index', ['sort_by' => 'name', 'sort_order' => 'asc']));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Chewbacca', 'Yoda']);
    }

    public function test_index_paginates_characters(): void
    {
        Character::factory()->count(20)->create();

        $response = $this->get(route('characters.index'));

        $response->assertStatus(200);
        $response->assertViewHas('characters', function ($characters) {
            return $characters->count() === 15;
        });
    }

    public function test_show_displays_character_details(): void
    {
        $planet = Planet::factory()->create(['name' => 'Tatooine']);
        $character = Character::factory()->create([
            'name' => 'Luke Skywalker',
            'homeworld_id' => $planet->id,
            'height' => '172',
            'gender' => 'male',
        ]);

        $response = $this->get(route('characters.show', $character));

        $response->assertStatus(200);
        $response->assertViewIs('characters.show');
        $response->assertViewHas('character');
        $response->assertSee('Luke Skywalker');
        $response->assertSee('172 cm');
        $response->assertSee('Tatooine');
    }

    public function test_show_handles_character_without_homeworld(): void
    {
        $character = Character::factory()->withoutHomeworld()->create(['name' => 'Unknown Character']);

        $response = $this->get(route('characters.show', $character));

        $response->assertStatus(200);
        $response->assertSee('Unknown Character');
        $response->assertSee('Unknown');
    }
}

