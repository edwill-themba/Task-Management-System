<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
//use SebastianBergmann\Comparator\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Supervisor;
use App\Models\User;



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
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $supervisors = Supervisor::pluck('id')->toArray();
        return [
            'task_name' => $this->faker->text($maxNbChars = 50),
            'task_date' => $this->faker->dateTimeBetween($start_date = "+1 days", $end_date = "+1 years"),
            'task_time' => $this->faker->time($format = 'H:i', $max = 'now'),
            'user_id' => $this->faker->randomElement($users),
            'supervisor_id' => $this->faker->randomElement($supervisors)
        ];
    }
}
