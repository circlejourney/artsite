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
	if(auth()->check()) return view('dashboard');
	else return view('welcome');
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
	
	Route::get("/work/new", [ArtworkController::class, 'create'])->name('art.create');
	Route::post("/work/new", [ArtworkController::class, 'store']);
	Route::get("/work/{path}/delete", [ArtworkController::class, 'showdelete'])->name('art.delete');
	Route::delete("/work/{path}/delete", [ArtworkController::class, 'delete']);
	Route::get("/work/{path}/edit", [ArtworkController::class, 'edit'])->name('art.edit');
	Route::put("/work/{path}/edit", [ArtworkController::class, 'update']);

});

require __DIR__.'/auth.php';

Route::get("/work/{path}", [ArtworkController::class, 'show'])->name("art");
Route::get("/{username}", [UserPageController::class, 'show'])->name('user');