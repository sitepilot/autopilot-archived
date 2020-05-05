<?php

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

Route::middleware('auth:sanctum')->get('/v1/user', function (Request $request) {
    return $request->user();
});

// Jobs
Route::middleware('auth:sanctum')->get('/v1/jobs/{job}', 'JobController@show');

// Server Groups
Route::middleware('auth:sanctum')->apiResource('/v1/groups', 'ServerGroupController');

// Server Hosts
Route::middleware('auth:sanctum')->apiResource('/v1/hosts', 'ServerHostController');
Route::middleware('auth:sanctum')->post('/v1/hosts/{host}/provision', 'ServerHostController@provision');
Route::middleware('auth:sanctum')->post('/v1/hosts/{host}/test', 'ServerHostController@test');
Route::middleware('auth:sanctum')->post('/v1/hosts/{host}/cert-renew', 'ServerHostController@certRenew');

// Server Users
Route::middleware('auth:sanctum')->apiResource('/v1/users', 'ServerUserController');
Route::middleware('auth:sanctum')->post('/v1/users/{user}/test', 'ServerUserController@test');

// Server Apps
Route::middleware('auth:sanctum')->apiResource('/v1/apps', 'ServerAppController');
Route::middleware('auth:sanctum')->post('/v1/apps/{app}/cert-request', 'ServerAppController@certRequest');

// Server Apps - WordPress
Route::middleware('auth:sanctum')->post('/v1/apps/{app}/wp/check-state', 'ServerAppController@wpCheckState');
Route::middleware('auth:sanctum')->post('/v1/apps/{app}/wp/install', 'ServerAppController@wpInstall');
Route::middleware('auth:sanctum')->post('/v1/apps/{app}/wp/login', 'ServerAppController@wpLogin');
Route::middleware('auth:sanctum')->post('/v1/apps/{app}/wp/search-replace', 'ServerAppController@wpSearchReplace');
Route::middleware('auth:sanctum')->post('/v1/apps/{app}/wp/update', 'ServerAppController@wpUpdate');

// Server Databases
Route::middleware('auth:sanctum')->apiResource('/v1/databases', 'ServerDatabaseController');
