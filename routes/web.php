<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPageController;
use App\Http\Controllers\ArtworkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name("home");

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile/delete', function(){
		return view("profile.delete");
	})->name('profile.destroy');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy']);
	Route::get("/profile/customise", [UserPageController::class, 'edit'])->name('profile.html.edit');
	Route::patch("/profile/customise", [UserPageController::class, 'update']);
	
	Route::get("/works/new", [ArtworkController::class, 'create'])->name('art.create');
	Route::post("/works/new", [ArtworkController::class, 'store']);
	Route::get("/works/{path}/delete", [ArtworkController::class, 'showdelete'])->name('art.delete');
	Route::delete("/works/{path}/delete", [ArtworkController::class, 'delete']);

});

require __DIR__.'/auth.php';

Route::get("/works/{path}", [ArtworkController::class, 'show'])->name("art");
Route::get("/{username}", [UserPageController::class, 'show'])->name('user');