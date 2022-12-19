<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = Task::factory()
            ->count(20)
            ->for(User::factory())
            ->for(Supervisor::factory())
            ->create();
    }
}
