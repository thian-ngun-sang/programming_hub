<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SharePostController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ReactionLogController;

Route::prefix('post')->group(function(){
	Route::post('make-comment', [CommentController::class, 'makeComment']);
	Route::get('get-comment', [CommentController::class, 'commentList']);
	Route::get('make-share-post', [SharePostController::class, 'makeSharePost']);
});

Route::prefix('reaction')->group(function(){
	Route::get('give-reaction', [ReactionLogController::class, 'giveReaction']);
	Route::get('drop-reaction', [ReactionLogController::class, 'dropReaction']);
});

Route::group(['middleware' => 'login_register'], function(){
	Route::get('/', [HomeController::class, 'homePage'])->name('home');

	Route::prefix('user')->group(function(){
		Route::get('logout', [UserController::class, 'logOutUser'])->name('user-logout');
		Route::get('account', [UserController::class, 'userProfilePage'])->name('user-account-page');
		Route::get('edit-info', [UserController::class, 'editProfile'])->name('user-edit-page');
		Route::post('update-info', [UserController::class, 'updateProfile'])->name('user-update');
		Route::get('change-password-page', [UserController::class, 'changePasswordPage'])->name('user-change-password-page');
		Route::post('change-password', [UserController::class, 'changePassword'])->name('user-change-password');
	});

	Route::get('upload-post-page/', [PostController::class, 'upload_post_page'])->name('upload-post-page');
	Route::post('upload-post/', [PostController::class, 'upload_post'])->name('upload-post');
	Route::get('post-list/', [PostController::class, 'post_list'])->name('post-list');
	Route::get('post/share-page/', [PostController::class, 'share_post'])->name('share-post');
	Route::get('post/{id}/', [PostController::class, 'get_post'])->name('get-post');
	Route::get('share-post/{id}/', [PostController::class, 'get_sharePost'])->name('share-post');
});

// authentication
Route::group(["middleware" => "loggedin_user"], function(){
	Route::get('login-page', [CustomAuthController::class, 'index'])->name('login');
	Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
	Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
	Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
});