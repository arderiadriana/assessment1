<?php

use App\Http\Controllers\crud;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [crud::class, 'index']);
Route::get('/show', [crud::class, 'show']);
Route::post('/store', [crud::class, 'store']);
Route::get('/edit/{id}', [crud::class, 'edit']);
Route::put('/update/{id}', [crud::class, 'update']);
Route::delete('/destroy/{id}', [crud::class, 'destroy']);
