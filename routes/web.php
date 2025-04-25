<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(get_page("home")->slug, [HomeController::class, 'index'])->name('home');
Route::get(get_page("live")->slug, [\App\Http\Controllers\GameController::class, 'live'])->name('live');
Route::get(get_page("areas")->slug, [\App\Http\Controllers\AreaController::class, 'index'])->name('areas');
Route::get(get_page("countries")->slug."/{slug}", [\App\Http\Controllers\CountryController::class, 'index'])->name('countries');
Route::get(get_page("leagues")->slug."/{slug}", [\App\Http\Controllers\LeagueController::class, 'index'])->name('leagues');
Route::get(get_page("clubs")->slug."/{slug}", [\App\Http\Controllers\TeamController::class, 'index'])->name('clubs');
Route::get(get_page("teamDetail")->slug."/{leagueSlug}/{clubSlug}", [\App\Http\Controllers\TeamController::class, 'details'])->name('teamDetail');
