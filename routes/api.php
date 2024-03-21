<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('public_message', [ApiController::class, 'publicMessage']);
// Route::middleware('cors')->post('estimate_message', [ApiController::class, 'estimateMessage']);
// Route::middleware('cors')->post('finished_message', [ApiController::class, 'finishedMessage']);
// Route::middleware('cors')->get('all_message', [ApiController::class, 'allMessage']);