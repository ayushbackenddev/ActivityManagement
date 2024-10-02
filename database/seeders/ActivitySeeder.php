<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < rand(5, 10); $i++) {
                Activity::create([
                    'user_id' => $user->id,
                    'activity_time' => now()->subDays(rand(0, 30)), // Random date within the last 30 days
                    'points' => 20, // Fixed 20 points for each activity
                ]);
            }
        }
    }
}
