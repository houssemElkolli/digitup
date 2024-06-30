<?php

use App\Http\Controllers\UserController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;





Route::post("/register", [UserController::class, "signUp"]);
Route::post("/login", [UserController::class, "signIn"]);


Route::controller(TaskController::class)
    ->middleware(['auth:sanctum'])
    ->prefix('tasks')
    ->group(function () {
        Route::get("/", "index")->withoutMiddleware("auth");
        Route::get("/deleted", "viewTrashed")->middleware("hasRole:admin");
        Route::post("/", "store");
        Route::get("/{id}", "show")->middleware("ownerOfTaskOrHasRole:admin");
        Route::put("/{id}", "update")->middleware("ownerOfTaskOrHasRole:admin");
        Route::delete("/{id}", "softDelete")->middleware("ownerOfTaskOrHasRole:admin");
        ;
    });


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
