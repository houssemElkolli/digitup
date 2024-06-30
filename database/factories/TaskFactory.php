<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->title(),
            "description" => $this->faker->text(),
            "status" => "done",
            "expiry_date" => $this->faker->date(),
            "user_id" => 1,
            "deleted_at" => null,

        ];
    }
}
