<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Client::class;
    public function definition(): array
    {
        return [
            'ref_no' => strtoupper('REF'. $this->faker->unique()->numerify('###')),
            'name' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
