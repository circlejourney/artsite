<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPageController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\CollectiveController;
use App\Http\Controllers\CollectiveFolderController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
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
	
	Route::get("/dashboard/tags", [TagController::class, 'index_manage'])->name("tags.manage");
	Route::get("/dashboard/tags/{tag}", [TagController::class, 'edit'])->name("tags.edit");
	Route::post("/dashboard/tags/{tag}", [TagController::class, 'store_or_update']);
	Route::patch("/dashboard/tags/{tag}", [TagController::class, 'store_or_update']);
	
	Route::get("/dashboard/art", [ArtworkController::class, 'manage'])->name("art.manage");
	Route::put("/dashboard/art", [ArtworkController::class, 'put']);
	
	Route::get("/art/new", [ArtworkController::class, 'create'])->name('art.create');
	Route::post("/art/new", [ArtworkController::class, 'store']);
	Route::get("/art/{path}/delete", [ArtworkController::class, 'showdelete'])->name('art.delete');
	Route::delete("/art/{path}/delete", [ArtworkController::class, 'delete']);
	Route::get("/art/{path}/edit", [ArtworkController::class, 'edit'])->name('art.edit');
	Route::put("/art/{path}/edit", [ArtworkController::class, 'update']);

	Route::controller(NotificationController::class)->group(function() {
		Route::get("/notifications", "index_faves")->name("notifications");
		Route::get("/notifications/favorites", "index_faves")->name("notifications.faves");
		
		Route::get("/notifications/follows", "index_follows")->name("notifications.follows");

		Route::get("/notification-count", "get_count")->name("notifications.get_count");
		
		Route::get("/notifications/invites", 'index_invites')->name("notifications.invites");
		Route::post("/notifications/invites", 'post_invite');
		
		Route::get("/notifications/collectives", "index_collectives")->name("notifications.collectives");
		Route::post("/notifications/collectives", "post_collectives");

		Route::get("/notifications/follow-feed", "index_feed")->name("notifications.feed");
		
		Route::put("/notifications/{page?}", "mark_read_many");
		Route::delete("/notifications/{page?}", "delete_many");
		Route::delete("/notification-ajax/{notification}", "delete_one")->name('notifications.delete-one');
		Route::put("/notification-read", "put_read")->name('notifications.put-read');

	});

	Route::post("/follow/{user}", [UserPageController::class, "follow"])->name("follow");

	Route::controller(MessageController::class)->group(function() {
		Route::get("messages", "index")->name("messages");
		Route::get("messages/outbox", "index")->name("messages.outbox");
		Route::get("/messages/new/{username?}", "create")->name("messages.create");
		Route::post("/messages/new/{username?}", "store");
		Route::get("messages/{message}", "show")->name("messages.show");
		Route::post("messages/{message}", "store");
	});
});

require __DIR__.'/auth.php';

Route::get("/art/{path}", [ArtworkController::class, 'show'])->name("art");

Route::controller(ArtworkController::class)->group(function () {
	Route::post('fave/{path}', 'fave')->name('fave');
	Route::delete('fave/{path}', 'unfave')->name('unfave');
});

/* Artist's gallery */
Route::get("/{username}/gallery", [FolderController::class, 'index_user'])->name("folders.index");
Route::get("/{username}/gallery/folder:{folder}/{all?}", [FolderController::class, 'show'])->name("folders.show");

/* Collectives */
Route::middleware("auth")->group(function(){
	Route::get("/co/new", [CollectiveController::class, 'create'])->name("collectives.create");
	Route::post("/co/new", [CollectiveController::class, 'store']);
	Route::get("/co/{collective}/dashboard/folders", [CollectiveFolderController::class, 'index_manage'])->name("collectives.folders.manage");
	Route::post("/co/{collective}/dashboard/folders", [CollectiveFolderController::class, 'store']);
	Route::get("/co/{collective}/dashboard/folders/folder:{folder}", [CollectiveFolderController::class, 'edit'])->name("collectives.folders.edit");

	Route::post("/co/{collective}", [CollectiveController::class, 'request_join']);
	Route::get("/co/{collective}/leave", [CollectiveController::class, 'show_leave'])->name("collectives.leave");
	Route::delete("/co/{collective}/leave", [CollectiveController::class, 'leave']);
	Route::get("/co/{collective}/delete", [CollectiveController::class, "show_destroy"])->name("collectives.delete");
	Route::delete("/co/{collective}/delete", [CollectiveController::class, "destroy"]);
	Route::get("/co/{collective}/edit", [CollectiveController::class, 'edit'])->name("collectives.edit");
	Route::patch("/co/{collective}/edit", [CollectiveController::class, 'update']);	
});

Route::get("/co", [CollectiveController::class, 'index'])->name("collectives.index");
Route::get("/co/{collective}", [CollectiveController::class, 'show'])->name("collectives.show");

Route::get("/co/{collective}/gallery", [CollectiveFolderController::class, 'index_collective'])->name("collectives.folders.index");
Route::get("/co/{collective}/gallery/folder:{folder}/{all?}", [CollectiveFolderController::class, 'show_collective'])->name("collectives.folders.show");

Route::get("/{username}/tags", [TagController::class, 'index_user'])->name("tags.user.index");
Route::get("/search", [TagController::class, 'show_global'])->name("tags.global.show");

Route::get("/{username}", [UserPageController::class, 'show'])->name('user');
Route::get("/{user}/invite", [UserPageController::class, 'invite'])->name('user.invite');
Route::post("/{user}/invite", [CollectiveController::class, 'invite']);
Route::get("/{username}/stats", [UserPageController::class, 'show_stats'])->name("stats");
Route::get("/{username}/faves", [UserPageController::class, 'index_faves'])->name("faves");