<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'isbn' => $this->generateISBN(),
            'description' => $this->faker->paragraphs(2, true),
            'published_year' => $this->faker->numberBetween(1800, 2024),
            'total_copies' => $this->faker->numberBetween(1, 10),
            'available_copies' => function (array $attributes) {
                return $this->faker->numberBetween(0, $attributes['total_copies']);
            },
        ];
    }

    private function generateISBN(): string
    {
        return $this->faker->numerify('978-###-#####-##-#');
    }
}
