<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPageController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\AdminPageController;
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

Route::middleware("role:admin,mod")->group(function(){
	Route::get("/admin", [AdminPageController::class, 'index'])->name("admin");
});

Route::middleware("permissions:manage_users")->group(function(){
	Route::get("/admin/users", [AdminPageController::class, 'index_users'])->name("admin.user.index");
	Route::get("/admin/users/{user}", [AdminPageController::class, 'edit_user'])->name("admin.user.edit");
	Route::put("/admin/users/{user}", [AdminPageController::class, 'update_user']);
});

Route::middleware("permissions:manage_roles")->group(function(){
	Route::get("/admin/roles", [AdminPageController::class, 'index_roles'])->name("admin.role.index");
	Route::get("/admin/roles/{role}", [AdminPageController::class, 'edit_role'])->name("admin.role.edit");
});

Route::middleware("permissions:manage_artworks")->group(function(){
	Route::get("/admin/works", [AdminPageController::class, 'index_artworks'])->name("admin.art.index");
	Route::get("/admin/works/{role}", [AdminPageController::class, 'edit_artworks'])->name("admin.art.edit");
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile/delete', function(){
		return view("profile.delete");
	})->name('profile.destroy');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy']);
	Route::get("/profile/customise", [UserPageController::class, 'edit'])->name('profile.html.edit');
	Route::patch("/profile/customise", [UserPageController::class, 'update']);
	
	Route::get("/post/new", [ArtworkController::class, 'create'])->name('art.create');
	Route::post("/post/new", [ArtworkController::class, 'store']);
	Route::get("/post/{path}/delete", [ArtworkController::class, 'showdelete'])->name('art.delete');
	Route::delete("/post/{path}/delete", [ArtworkController::class, 'delete']);
	Route::get("/post/{path}/edit", [ArtworkController::class, 'edit'])->name('art.edit');
	Route::put("/post/{path}/edit", [ArtworkController::class, 'update']);

	Route::get("/manage-folders", [FolderController::class, 'index_manage'])->name("folders.manage");
	Route::post("/manage-folders", [FolderController::class, 'store']);
	Route::get("/manage-folders/{folder}", [FolderController::class, 'edit'])->name("folders.edit");
	Route::put("/manage-folders/{folder}", [FolderController::class, 'update']);
	Route::delete("/manage-folders/{folder}", [FolderController::class, 'destroy']);
});

require __DIR__.'/auth.php';

Route::get("/post/{path}", [ArtworkController::class, 'show'])->name("art");
Route::get("/{username}/folders", [FolderController::class, 'index_user'])->name("folders.index");
Route::get("/{username}/folder:{folder}", [FolderController::class, 'show'])->name("folders.show");
Route::get("/{username}", [UserPageController::class, 'show'])->name('user');