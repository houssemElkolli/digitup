<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $includeDeletedTasks = $request->query("includeDeletedTasks");

            if ($includeDeletedTasks === "true" && Auth::user()->hasRole("admin")) {

                $tasks = Task::withTrashed()->get();
            } else
                $tasks = Task::all();

            if (is_null($tasks)) {
                return response()->json(["message" => "no data", "data" => $tasks], 200);

            }

            return response()->json(["message" => "found successfully", "data" => $tasks], 200);

        } catch (\Throwable $th) {
            if (env("APP_ENV" === "local")) {
                return response()->json(["error" => $th->getMessage()], 500);
            }
            return response()->json(["error" => "somthing went wrong , please try again later"], 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'expiry_date' => $request->expiry_date,
                'user_id' => Auth::user()->id,
            ]);

            return response()->json(["message" => "created successfully", "data" => $task,], 201);
        } catch (\Throwable $th) {
            if (env("APP_ENV" === "local")) {
                return response()->json(["error" => $th->getMessage()], 500);
            }
            return response()->json(["error" => "somthing went wrong , please try again later"], 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $task = Task::withTrashed()->find($id);

            if (is_null($task) || ($task->trashed() && Auth::user()->hasRole("user"))) {
                return response()->json(["message" => "not found", "data" => []], 404);
            }

            return response()->json(["message" => "found successfully", "data" => $task], 200);


        } catch (\Throwable $th) {
            if (env("APP_ENV" === "local")) {
                return response()->json(["error" => $th->getMessage()], 500);
            }
            return response()->json(["error" => "somthing went wrong , please try again later"], 500);

        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, $task)
    {
        try {
            $task = Task::withTrashed()->find($task);

            if (is_null($task) || ($task->trashed() && Auth::user()->hasRole("user"))) {
                return response()->json(["message" => "not found", "data" => []], 404);
            }


            $task = $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'expiry_date' => $request->expiry_date,
            ]);

            return response()->json(["message" => "updated successfully"], 201);



        } catch (\Throwable $th) {
            if (env("APP_ENV" === "local")) {
                return response()->json(["error" => $th->getMessage()], 500);
            }
            return response()->json(["error" => "somthing went wrong , please try again later"], 500);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        try {
            $task = Task::find($id);

            if (is_null($task)) {
                return response()->json(["message" => "not found", "data" => []], 404);
            }

            $task = $task->delete();

            return response()->json([], 204);


        } catch (\Throwable $th) {
            if (env("APP_ENV" === "local")) {
                return response()->json(["error" => $th->getMessage()], 500);
            }
            return response()->json(["error" => "somthing went wrong , please try again later"], 500);

        }
    }

    /**
     * Display a listing of the resource.
     */
    public function viewTrashed()
    {
        try {

            $task = Task::onlyTrashed()->get();

            if (is_null($task)) {
                return response()->json(["message" => "no data", "data" => $task], 200);
            }
            return response()->json(["message" => "found successfully", "data" => $task], 200);

        } catch (\Throwable $th) {
            if (env("APP_ENV" === "local")) {
                return response()->json(["error" => $th->getMessage()], 500);
            }
            return response()->json(["error" => "somthing went wrong , please try again later"], 500);
        }
    }
}
