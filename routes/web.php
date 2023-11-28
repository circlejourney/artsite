<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPageController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\CollectiveController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\TagController;
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


/* Admin management routes */
Route::middleware("role:admin,mod")->group(function(){
	Route::get("/admin", [AdminPageController::class, 'index'])->name("admin");
});

Route::middleware("permissions:manage_users")->group(function(){
	Route::get("/admin/users", [AdminPageController::class, 'index_users'])->name("admin.user.index");
	Route::get("/admin/users/{user}", [AdminPageController::class, 'edit_user'])->name("admin.user.edit");
	Route::put("/admin/users/{user}", [AdminPageController::class, 'update_user']);
	Route::delete("/admin/users/{user}/delete", [ProfileController::class, 'destroy']);
});

Route::middleware("permissions:manage_roles")->group(function(){
	Route::get("/admin/roles", [AdminPageController::class, 'index_roles'])->name("admin.role.index");
	Route::get("/admin/roles/{role}", [AdminPageController::class, 'edit_role'])->name("admin.role.edit");
	Route::put("/admin/roles/{role}", [AdminPageController::class, 'update_role']);
});

Route::middleware("permissions:manage_artworks")->group(function(){
	Route::get("/admin/works", [AdminPageController::class, 'index_artworks'])->name("admin.art.index");
	Route::get("/admin/works/{role}", [AdminPageController::class, 'edit_artworks'])->name("admin.art.edit");
});

Route::middleware('auth', 'verified')->group(function () {
	/* Self management routes */
    Route::get('/dashboard/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/dashboard/account', [ProfileController::class, 'update'])->name('profile.update');
	Route::get('/dashboard/account/delete', function(){ return view("profile.delete"); })->name('profile.destroy');
    Route::delete('/dashboard/account/delete', [ProfileController::class, 'destroy']);
	Route::get("/dashboard/profile", [UserPageController::class, 'edit'])->name('profile.html.edit');
	Route::patch("/dashboard/profile", [UserPageController::class, 'update']);

	Route::get('/dashboard/invites', [InviteController::class, 'manage'])->name('invites');
	Route::post('/dashboard/invites', [InviteController::class, 'generate']);
	
	Route::get("/dashboard/folders", [FolderController::class, 'index_manage'])->name("folders.manage");
	Route::post("/dashboard/folders", [FolderController::class, 'store']);
	Route::get("/dashboard/folders/folder:{folder}", [FolderController::class, 'edit'])->name("folders.edit");
	Route::put("/dashboard/folders/folder:{folder}", [FolderController::class, 'update']);
	Route::delete("/dashboard/folders/folder:{folder}", [FolderController::class, 'destroy']);
	
	Route::get("/dashboard/art", [ArtworkController::class, 'manage'])->name("art.manage");
	Route::put("/dashboard/art", [ArtworkController::class, 'put']);
	
	Route::get("/art/new", [ArtworkController::class, 'create'])->name('art.create');
	Route::post("/art/new", [ArtworkController::class, 'store']);
	Route::get("/art/{path}/delete", [ArtworkController::class, 'showdelete'])->name('art.delete');
	Route::delete("/art/{path}/delete", [ArtworkController::class, 'delete']);
	Route::get("/art/{path}/edit", [ArtworkController::class, 'edit'])->name('art.edit');
	Route::put("/art/{path}/edit", [ArtworkController::class, 'update']);
});

require __DIR__.'/auth.php';

Route::get("/art/{path}", [ArtworkController::class, 'show'])->name("art");

/* Artist's gallery */
Route::get("/{username}/gallery", [FolderController::class, 'index_user'])->name("folders.index");
Route::get("/{username}/gallery/folder:{folder}", [FolderController::class, 'show'])->name("folders.show");

/* Groups */
Route::get("/co/new", [CollectiveController::class, 'create'])->name("collectives.create");
Route::post("/co/new", [CollectiveController::class, 'store']);
Route::get("/co/{url}", [CollectiveController::class, 'show'])->name("collectives.show");
Route::get("/co/{url}/edit", [CollectiveController::class, 'edit'])->name("collectives.edit");
Route::patch("/co/{url}/edit", [CollectiveController::class, 'update']);

Route::get("/{username}/tags", [TagController::class, 'index_user'])->name("tags.user.index");
Route::get("/tags:{tag}", [TagController::class, 'show_global'])->name("tags.global.show");

Route::get("/{username}", [UserPageController::class, 'show'])->name('user');