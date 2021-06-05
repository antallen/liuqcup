<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Manager\accountsController;
use App\Http\Controllers\Manager\authController;
use App\Http\Controllers\Manager\storesController;
use App\Http\Controllers\Manager\storesLocksController;
use App\Http\Controllers\Manager\funcsController;
use App\Http\Controllers\Manager\classesController;
use App\Http\Controllers\Manager\agentsController;
use App\Http\Controllers\Manager\customersController;
use App\Http\Controllers\Manager\registerController;
use App\Http\Controllers\Manager\socialController;
use App\Http\Controllers\News\newsController;
use App\Http\Controllers\News\addnewsController;
use App\Http\Controllers\News\updatenewsController;
use App\Http\Controllers\Rent\rentController;
use App\Http\Controllers\Rent\storesloginController;
use App\Http\Controllers\Rent\cupsController;
use App\Http\Controllers\Rent\cusrentController;
use App\Http\Controllers\Rent\aberrantController;
use App\Http\Controllers\Record\rentlogsController;
use App\Http\Controllers\Record\rentcupController;
use App\Http\Controllers\Stock\stocksController;
use App\Http\Controllers\Lottos\lottosController;
use SebastianBergmann\CodeCoverage\CrapIndex;

Route::apiResource('manager/accounts/v1/auths',authController::class)->only('store');
Route::apiResource('manager/accounts/v1/frozens',authController::class)->only('update');
Route::apiResource('manager/accounts/v1/lists',accountsController::class)->only('index');
Route::apiResource('manager/accounts/v1/creates',accountsController::class)->only('store');
Route::apiResource('manager/accounts/v1/renews',accountsController::class)->only('update');
Route::apiResource('manager/v1/stores/lists',storesController::class)->only('index');
Route::apiResource('manager/v1/stores/creates',storesController::class)->only('store');
Route::apiResource('manager/v1/stores',storesController::class)->only('update');
Route::apiResource('manager/v1/stores/frozens',storesLocksController::class)->only('store');
Route::apiResource('manager/v1/stores/querys',storesController::class)->only('show');
Route::apiResource('manager/v1/stores/agent',agentsController::class)->only('store');
Route::apiResource('manager/v1/funcs/config',funcsController::class)->only('store');
Route::apiResource('manager/v1/classes/config',classesController::class)->only('store');
Route::apiResource('manager/v1/customers/config',customersController::class)->only('store');
Route::apiResource('manager/v1/customers/register',registerController::class);
Route::apiResource('rent/v1/customers/rent',rentController::class)->only('store');
Route::apiResource('rent/v1/stores/rent',rentController::class)->only('update');
Route::apiResource('rent/v1/stores/qrcode',storesloginController::class)->only('store');
Route::apiResource('rent/v1/stores/login',storesloginController::class)->only('index');
Route::apiResource('rent/v1/stores/checks',cupsController::class)->only('update');
Route::apiResource('rent/v1/stores/rent/list',cupsController::class)->only('store');
Route::apiResource('rent/v1/stores/rent/show',cupsController::class)->only('index');
Route::apiResource('rent/v1/customers/rent/list',cusrentController::class)->only('store');
Route::apiResource('rent/v1/customers/rent/checks',cusrentController::class)->only('update');
Route::apiResource('manager/v1/customers/login',customersController::class)->only('update');
Route::apiResource('records/v1/customers/logs',rentlogsController::class)->only('show');
Route::apiResource('records/v1/stores/rentcup',rentcupController::class)->only('show');
Route::apiResource('records/v1/stores/rentcuplist',rentcupController::class)->only('index');
Route::apiResource('records/v1/stores/stocklist',stocksController::class)->only('index');
Route::apiResource('records/v1/stores/pushlist',stocksController::class)->only('show');
Route::apiResource('records/v1/stores/aberrantlist',aberrantController::class)->only('index');
Route::apiResource('news/v1/news/list',newsController::class)->only('index');
//Route::apiResource('news/v1/news',newsController::class)->only('create');
Route::apiResource('news/v1/news',newsController::class)->only('show');
Route::apiResource('news/v1/news/query',newsController::class)->only('store');
Route::apiResource('news/v1/news/update',updatenewsController::class)->only('store');
Route::apiResource('news/v1/news',newsController::class)->only('destroy');
Route::apiResource('news/v1/news/create',addnewsController::class)->only('store');
Route::apiResource('lottos/v1/news',lottosController::class);
Route::apiResource('manager/v1/stores/social',socialController::class);
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
