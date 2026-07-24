<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\App\Models\Meeting;
use Workbench\App\Models\Room;
use Workbench\App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // The panel auto-authenticates as this user, see AutoLogin middleware.
        User::factory()->create([
            'name' => 'Workbench User',
            'email' => 'workbench@example.com',
        ]);

        $rooms = collect(['Blue Room', 'Green Room', 'Orange Room'])
            ->map(fn (string $name, int $i) => Room::factory()->create([
                'name' => $name,
                'color' => ['#3b82f6', '#10b981', '#f59e0b'][$i],
            ]))
        ;

        // A spread across the current week so every view has something to show.
        $rooms->each(
            fn (Room $room) => Meeting::factory()
                ->count(4)
                ->create(['room_id' => $room->id])
        );

        Meeting::factory()->allDay()->create([
            'room_id' => $rooms->first()->id,
            'title' => 'All-day offsite',
        ]);

        Meeting::factory()->locked()->create([
            'room_id' => $rooms->first()->id,
            'title' => 'Locked — drag/resize must revert',
            'starts_at' => now()->startOfWeek()->addDays(2)->setTime(9, 0),
            'ends_at' => now()->startOfWeek()->addDays(2)->setTime(10, 30),
        ]);
    }
}
