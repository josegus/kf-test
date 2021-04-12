<?php

use App\Http\Controllers\CoopsController;
use App\Http\Controllers\FundCoopController;
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

Route::view('/', 'welcome');

Route::get('coops', [CoopsController::class, 'index'])->name('coops.index');
Route::get('coops/{coop}', [CoopsController::class, 'show'])->name('coops.show');

Route::post('coops/{coop}/fund', FundCoopController::class)->middleware('auth')->name('coops.fund');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
