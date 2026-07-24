<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Meeting;

/**
 * @extends Factory<Meeting>
 */
class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        $start = now()
            ->startOfWeek()
            ->addDays(fake()->numberBetween(0, 6))
            ->setTime(fake()->numberBetween(8, 16), fake()->randomElement([0, 30]))
        ;

        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addHour(),
            'all_day' => false,
            'locked' => false,
        ];
    }

    public function allDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'all_day' => true,
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->endOfDay(),
        ]);
    }

    public function locked(): static
    {
        return $this->state(fn () => ['locked' => true]);
    }
}
