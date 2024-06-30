<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwnerOfTaskOrHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        $taskId = $request->route()->parameter('id');

        $task = Task::withTrashed()->find($taskId);

        if (is_null($task) || ($task->trashed() && Auth::user()->hasRole("user"))) {
            return response()->json(["message" => "not found"], 404);
        }

        if (Auth::user()->isOwnerOfTask($task) || Auth::user()->hasRole($role)) {
            return $next($request);
        }
        return response()->json(["message" => "unauthorized"], 401);
    }
}
