<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metode = [
            'Luring',
            'Webinar',
            'SPL',
            'Video Learning',
        ];

        return [
            'name' => $this->faker->name,
            'jadwal_name' => $this->faker->name,
            'is_prakerja' => mt_rand(0, 1),
            'metode' => $metode[mt_rand(0, 3)],
            'date' => $this->faker->name,
            'jam' => $this->faker->name,
            'price' => mt_rand(100000, 1000000),
            'user_create' => 1,
            'user_update' => 1,
        ];
    }
}
