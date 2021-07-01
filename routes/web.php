<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Auth;

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
    if(Auth::user()) {
        return redirect()->route('blogs.index');
    }
    else {
        return view('welcome');
    }
});

Route::get('/dashboard', function () {
    return redirect()->route('blogs.index');
})->middleware(['auth'])->name('dashboard');


Route::get('/profile/{user}', [ProfileController::class, 'show'])
 ->middleware(['auth'])->name('profile.show');
 
Route::get('/profile/{profile}/getSocialLinks', [ProfileController::class, 'getSocialLinks'])
->middleware(['auth'])->name('profile.getSocialLinks');

Route::post('/profile/update', [ProfileController::class, 'update'])
->middleware(['auth'])->name('profile.update');

Route::resource('blogs', BlogController::class)
->middleware(['auth']);

require __DIR__.'/auth.php';
