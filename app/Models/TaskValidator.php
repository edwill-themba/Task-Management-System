<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TaskValidator extends Model
{
    use HasFactory;

    /**
     * Checks if user has another task on the date
     * @param $task_date
     * @param $use_id; 
     */
    public function checkUserTask($task_date, $user_id, $user_type)
    {
        if ($user_type == "supervisor") {
            $user_task = DB::table('tasks')
                ->where('task_date', '=', $task_date)
                ->where('supervisor_id', '=', $user_id)
                ->where('user_id', '=', null)
                ->get();
        } else {
            $user_task = DB::table('tasks')
                ->where('task_date', '=', $task_date)
                ->where('user_id', '=', $user_id)
                ->get();
        }
        return $user_task;
    }

    /**
     * Removes tasks that time has expired
     */
    public function removedOldTask()
    {
        $today_date = date('Y-m-d');
        $oldtask = DB::table('tasks')->where('task_date', '<', $today_date)->delete();
    }
}
