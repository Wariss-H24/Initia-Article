<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),   // titre aléatoire
            'content' => $this->faker->paragraph(), // contenu aléatoire
            'image' => null,                        // tu peux mettre $this->faker->imageUrl() si tu veux
        ];
    }
}
