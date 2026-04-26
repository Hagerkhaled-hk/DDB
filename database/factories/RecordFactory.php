<?php

namespace Database\Factories;

use App\Models\Record;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject' => $this->faker->randomElement(['Math', 'English', 'Science', 'History', 'Physics', 'Chemistry']),
        ];
    }
}
