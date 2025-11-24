<?php

namespace Database\Factories;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character>
 */
class CharacterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Character::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'swapi_id' => fake()->unique()->numerify('###'),
            'name' => fake()->unique()->name(),
            'height' => fake()->numberBetween(150, 220),
            'mass' => fake()->numberBetween(50, 150),
            'hair_color' => fake()->randomElement(['black', 'brown', 'blond', 'red', 'gray', 'white', 'none', 'unknown']),
            'skin_color' => fake()->randomElement(['fair', 'gold', 'white', 'light', 'green', 'blue', 'brown', 'pale', 'unknown']),
            'eye_color' => fake()->randomElement(['blue', 'yellow', 'red', 'brown', 'blue-gray', 'black', 'orange', 'hazel', 'pink', 'unknown']),
            'birth_year' => fake()->randomElement(['19BBY', '41.9BBY', '92BBY', '102BBY', '33BBY', '46BBY', '82BBY', '54BBY', 'unknown']),
            'gender' => fake()->randomElement(['male', 'female', 'n/a', 'hermaphrodite', 'none']),
            'homeworld_id' => Planet::factory(),
        ];
    }

    /**
     * Indicate that the character has no homeworld.
     */
    public function withoutHomeworld(): static
    {
        return $this->state(fn (array $attributes) => [
            'homeworld_id' => null,
        ]);
    }
}
