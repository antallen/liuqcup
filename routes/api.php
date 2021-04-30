<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Manager\accountsController;
use App\Http\Controllers\Manager\authController;
use SebastianBergmann\CodeCoverage\CrapIndex;

Route::apiResource('manager/accounts/v1/auths',authController::class)->only('store');
Route::apiResource('manager/accounts/v1/frozens',authController::class)->only('update');
Route::apiResource('manager/accounts/v1/lists',accountsController::class)->only('index');
Route::apiResource('manager/accounts/v1/creates',accountsController::class)->only('store');
Route::apiResource('manager/accounts/v1/renews',accountsController::class)->only('update');
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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

*/
