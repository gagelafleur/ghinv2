<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeeController;


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

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/scrape', [HomeController::class, 'scrape'])->name('scrape');

Auth::routes();
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/score/add', [ScoreController::class, 'getAddScore'])->name('score.add');
Route::post('/score/save', [ScoreController::class, 'postSaveCourse'])->name('score.save');
Route::get('/course/autocomplete', [CourseController::class, 'autocomplete'])->name('autocomplete');
Route::get('/tees/view', [TeeController::class, 'view'])->name('tees.json');
