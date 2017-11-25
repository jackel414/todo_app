<?php

use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', function() {
	$week = Carbon::now()->startOfWeek()->toDateString();

	$tasks = Task::where('week', $week)->get();

	return response()->json($tasks, 200);
});

Route::post('/tasks', function(Request $request) {
	$task = new Task;

	$task->fill($request->all());

	$today = Carbon::now();

	$task->week = $today->startOfWeek()->toDateString();

	$task->save();

	return response()->json(['status' => 'success', 'task' => $task], 201);
});

Route::post('/tasks/{task}/do-today', function(Task $task) {
	$today = Carbon::now();

	$task->day = $today->toDateString();

	$task->save();

	return response()->json(['status' => 'Task successfully scheduled for today.'], 200);
});

Route::post('/tasks/{task}/complete', function(Task $task) {
	$task->complete = true;

	$task->save();

	return response()->json(['status' => 'Task successfully marked complete.'], 200);
});