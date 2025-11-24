<?php

namespace Database\Factories;

use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Planet>
 */
class PlanetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Planet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'swapi_id' => fake()->unique()->numerify('###'),
            'name' => fake()->unique()->randomElement([
                'Tatooine',
                'Alderaan',
                'Yavin IV',
                'Hoth',
                'Dagobah',
                'Bespin',
                'Endor',
                'Naboo',
                'Coruscant',
                'Kamino',
                'Geonosis',
                'Utapau',
                'Mustafar',
                'Kashyyyk',
                'Polis Massa',
                'Mygeeto',
                'Felucia',
                'Cato Neimoidia',
                'Saleucami',
            ]).' '.fake()->numberBetween(1, 100),
            'rotation_period' => fake()->numberBetween(10, 50),
            'orbital_period' => fake()->numberBetween(200, 500),
            'diameter' => fake()->numberBetween(5000, 15000),
            'climate' => fake()->randomElement(['arid', 'temperate', 'tropical', 'frozen', 'murky']),
            'gravity' => fake()->randomElement(['1 standard', '0.5 standard', '1.5 standard']),
            'terrain' => fake()->randomElement(['desert', 'grasslands', 'mountains', 'jungle', 'rainforests', 'tundra', 'ice caves', 'mountain ranges', 'swamp', 'gas giant']),
            'surface_water' => fake()->numberBetween(0, 100),
            'population' => fake()->numberBetween(1000000, 10000000000),
        ];
    }
}
