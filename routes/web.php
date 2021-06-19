<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/profile/{user}', [ProfileController::class, 'show'])
 ->middleware(['auth'])->name('profile.show');
 
Route::get('/profile/{profile}/getSocialLinks', [ProfileController::class, 'getSocialLinks'])
->middleware(['auth'])->name('profile.getSocialLinks');

Route::post('/profile/update', [ProfileController::class, 'update'])
->middleware(['auth'])->name('profile.update');

require __DIR__.'/auth.php';
