<?php

use App\Http\Controllers\API\Auth\AuthAPIController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix("v1")->group(function () {
    Route::post("/register", [AuthAPIController::class, "register"]);
    Route::post("/login", [AuthAPIController::class, "login"]);

    Route::middleware("auth:sanctum")->group(function () {
        Route::prefix("auth")->group(function () {
            Route::get("/profile", [AuthAPIController::class, "profile"]);
            Route::get("/logout", [AuthAPIController::class, "logout"]);
            Route::get("/refresh", [AuthAPIController::class, "refresh"]);
            Route::get("/logout-all", [AuthAPIController::class, "logoutAll"]);
        });

        Route::apiResource('user', UserController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get("/deleted-users-list",[UserController::class,"deletedUsers"]);
        Route::get("/restore/{id}",[UserController::class,"restoreUser"]);
    });
});

/*
Generated API Resource URLs
Method	    URL	            Action	        Controller      Method
GET	        /users	        Get all         users	        index()
POST	    /users	        Create          user	        store()
GET	        /users/{id}	    Get single      user	        show()
PUT/PATCH	/users/{id}	    Update          user	        update()
DELETE	    /users/{id}	    Delete          user	        destroy()
*/
