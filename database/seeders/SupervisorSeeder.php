<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supervisor;
use App\Models\Task;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supervisor = Supervisor::factory()
            ->count(3)
            ->has(Task::factory()->count(3))
            ->create();
    }
}
