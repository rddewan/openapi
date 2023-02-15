<?php

use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\SignUpController;
use App\Http\Controllers\Api\v1\ToDo\ToDoController;
use App\Http\Controllers\Api\v1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'v1'], function () {

    Route::get('validate_token',function (){
        return ['message'=> 'true'];
    })->middleware('auth:sanctum');


    Route::post('register',[SignUpController::class,'register']);
    Route::post('login',[LoginController::class,'login']);

    Route::group(['prefix'=>'user'], function () {
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('getUser/{id}', [UserController::class, 'getUser']);
            Route::post('delete', [UserController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'todo'],function (){

        Route::group(['middleware' =>'auth:sanctum'],function (){
            Route::get('getToDos/{id}',[ToDoController::class,'getToDos']);
            Route::get('getToDoList/{id}',[ToDoController::class,'getToDoList']);
            Route::get('getToDo/{id}',[ToDoController::class,'getToDo']);


            Route::post('addToDo',[ToDoController::class,'store']);
            Route::post('updateToDo',[ToDoController::class,'update']);
            Route::post('deleteToDo',[ToDoController::class,'destroy']);
        });
    });

});
