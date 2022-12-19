<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskValidator;
use Illuminate\Support\Facades\Auth;
use DB;


class TaskController extends Controller
{
    public function __construct()
    {
       //deletes tasks thats dates has expired
        $task_validator = (new TaskValidator())->removedOldTask();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = DB::table('tasks')
            ->select('task_name', 'task_time', 'task_date', 'id')
            ->orderBy('created_at', 'DESC')
            ->get();
        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Display a listing of the resource of a specific user.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_task()
    {
        $tasks = '';
        $user_type = Auth::user()->type;
        $user_id = Auth::user()->id;
        if ($user_type == "supervisor") {
            $tasks = DB::table('supervisors')
                ->join('tasks', 'tasks.supervisor_id', '=', 'supervisors.id')
                ->select('tasks.*', 'supervisors.name', 'supervisors.type')
                ->where('supervisors.id', '=', $user_id)
                ->orderBy('tasks.created_at', 'DESC')
                ->get();
        } else {
            $tasks = DB::table('users')
                ->join('tasks', 'tasks.user_id', '=', 'users.id')
                ->select('tasks.*', 'users.name', 'users.type')
                ->where('users.id', '=', $user_id)
                ->orderBy('tasks.created_at', 'DESC')
                ->get();

        }
        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Display a listing of the resource of a specific user
     * assigned by a supervisor
     * @return \Illuminate\Http\Response
     */
    public function my_assigned_tasks()
    {
        $tasks = '';
        $user_type = Auth::user()->type;
        $user_id = Auth::user()->id;

        if ($user_type == 'subordinate') {
            $tasks = DB::table('tasks')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->join('supervisors', 'tasks.supervisor_id', '=', 'supervisors.id')
                ->select('tasks.*', 'users.type', 'supervisors.name')
                ->where('users.id', '=', $user_id)
                ->whereNotNull('tasks.supervisor_id')
                ->orderBy('tasks.created_at', 'DESC')
                ->get();
        }
        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Display a listing of the resource of users
     * 
     * @return \Illuminate\Http\Response
     */
    public function my_users()
    {
        $users = DB::table('users')
            ->select('name', 'email', 'id', 'type')
            ->where('type', '=', 'subordinate')
            ->get();

        return response()->json(['users' => $users], 200);
    }

    /**
     * Gets dates where a user  have tasks
     * 
     * @return \Illuminate\Http\Response
     */
    public function get_users_task($user_id)
    {

        $tasks = DB::table('users')
            ->join('tasks', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.task_date', 'tasks.id', 'tasks.task_name', 'users.name')
            ->where('users.id', '=', $user_id)
            ->get();
        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'task_name' => 'required|min:5|max:191',
            'task_date' => 'required|after:today',
            'task_time' => 'required',
            'user_id' => 'nullable',
            'supervisor_id' => 'nullable'
        ]);

        $task = new Task;
        $task->task_name = $request->input('task_name');
        $task->task_date = $request->input('task_date');
        $task->task_time = $request->input('task_time');
        $user_type = Auth::user()->type;
        // create instance of task validator
        $task_validator = new TaskValidator();
        // checks the authorized user type
        if ($user_type == "supervisor") {
            $supervisor_task = $task_validator->checkUserTask($task->task_date, Auth::user()->id, Auth::user()->type);
            if ($supervisor_task == '[]') {
                $task->supervisor_id = Auth::user()->id;
                $task->save();
                return response()->json(['task' => $task], 201);
            } else {
                return response()->json(['message' => 'you have a task on this day,please choose another day'], 422);
            }
        } else {
            $subordinate_task = $task_validator->checkUserTask($task->task_date, Auth::user()->id, Auth::user()->type);
            if ($subordinate_task == '[]') {
                $task->user_id = Auth::user()->id;
                $task->save();
                return response()->json(['task' => $task], 201);
            } else {
                return response()->json(['message' => 'you have a task on this day,please choose another day'], 422);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_user_task(Request $request)
    {
        $this->validate($request, [
            'task_name' => 'required|min:5|max:191',
            'task_date' => 'required|after:today',
            'task_time' => 'required',
            'user_id' => 'required',
            'supervisor_id' => 'nullable'
        ]);
        $task = new Task;
        $task->task_name = $request->input('task_name');
        $task->task_date = $request->input('task_date');
        $task->task_time = $request->input('task_time');
        $task->user_id = $request->input('user_id');
        $user_type = Auth::user()->type;
         // create instance of task validator
        $task_validator = new TaskValidator();
         // checks the authorized user type
        if ($user_type == "supervisor") {
            $supervisor_task = $task_validator->checkUserTask($task->task_date, Auth::user()->id, Auth::user()->type);
            if ($supervisor_task == '[]') {
                $task->supervisor_id = Auth::user()->id;
                $task->save();
                return response()->json(['task' => $task], 201);
            } else {
                return response()->json(['message' => 'you have a task on this day,please choose another day'], 422);
            }
        } else {
            return response()->json(['message' => 'you are not authorized to peform this operation'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'task_name' => 'required|min:5|max:191',
            'task_date' => 'required|after:today',
            'task_time' => 'required',
            'user_id' => 'nullable',
            'supervisor_id' => 'nullable'
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        
        // find task an instance of task
        $task = Task::find($id);
        $task->task_name = $request->input('task_name');
        $task->task_date = $request->input('task_date');
        $task->task_time = $request->input('task_time');
        // create an instance of task validator

        $task_validator = new TaskValidator();
        // check user type
        if (Auth::user()->type == 'subordinate') {
            if (Auth::user()->id == $task->user_id && $task->supervisor_id == null) {
                $subordinate_task = $task_validator->checkUserTask($task->task_date, Auth::user()->id, Auth::user()->type);
                if ($subordinate_task == '[]') {
                    $task->user_id = Auth::user()->id;
                    $task->save();
                    return response()->json(['task' => $task], 201);
                } else {
                    return response()->json(['message' => 'you have a task on this day,please choose another day'], 422);
                }
            } else {
                return response()->json(['message' => 'you are not allowed to perform this operation'], 401);
            }
        } elseif (Auth::user()->type == 'supervisor') {
            if (Auth::user()->id == $task->supervisor_id) {
                $supervisor_task = $task_validator->checkUserTask($task->task_date, Auth::user()->id, Auth::user()->type);
                if ($supervisor_task == '[]') {
                    $task->supervisor_id = Auth::user()->id;
                    $task->save();
                    return response()->json(['task' => $task], 201);
                } else {
                    return response()->json(['message' => 'you have a task on this day,please choose another day'], 422);
                }
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_supervisor_task(Request $request, $id)
    {
        $this->validate($request, [
            'task_name' => 'required|min:5|max:191',
            'task_date' => 'required|after:today',
            'task_time' => 'required',
            'user_id' => 'nullable',
            'supervisor_id' => 'nullable'
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        
        // find task an instance of task
        $task = Task::find($id);
        $task->task_name = $request->input('task_name');
        $task->task_date = $request->input('task_date');
        $task->task_time = $request->input('task_time');
        // create an instance of task validator

        $task_validator = new TaskValidator();

        if (Auth::user()->type == 'supervisor') {
            if (Auth::user()->id == $task->supervisor_id && $task->user_id !== null) {
                $supervisor_task = $task_validator->checkUserTask($task->task_date, Auth::user()->id, Auth::user()->type);
                if ($supervisor_task == '[]') {
                    $task->supervisor_id = Auth::user()->id;
                    $task->save();
                    return response()->json(['task' => $task], 201);
                } else {
                    return response()->json(['message' => 'you have a task on this day,please choose another day'], 422);
                }
            } else {
                return response()->json(['message' => 'you are not authorized'], 401);
            }
        } else {
            return response()->json(['message' => 'only supervisors are allowed to perfom this task'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
         // check user type
        if (Auth::user()->type == 'subordinate') {
            if (Auth::user()->id == $task->user_id && $task->supervisor_id == null) {
                $task->delete();
                return response()->json(['message' => 'task was successfully deleted'], 200);
            } else {
                return response()->json(['message' => 'you are not allowed to perform this operation'], 401);
            }
        } else if (Auth::user()->type == 'supervisor') {
            if (Auth::user()->id == $task->supervisor_id) {
                $task->delete();
                return response()->json(['message' => 'task was successfully deleted'], 200);
            } else {
                return response()->json(['message' => 'you are not allowed to perform this operation'], 401);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_supervisor_user($id)
    {
        $task = Task::find($id);
        if (Auth::user()->type == 'supervisor') {
            if (Auth::user()->id == $task->supervisor_id && $task->user_id !== null) {
                $task->delete();
                return response()->json(['message' => 'task was successfully deleted'], 200);
            } else {
                return response()->json(['message' => 'you are not allowed to perform this operation'], 401);
            }
        }
    }

    /**
     * Search the specified resource from storage.
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $task = Task::where("task_name", "like", "%" . $name . "%")->get();

        if (count($task) > 0) {
            return response()->json(['task' => $task], 200);
        } else {
            return response()->json(['message' => 'Task Not Found'], 404);
        }
    }
}


