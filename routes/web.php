<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PostController;

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



Route::middleware(['auth', 'verified'])->name('admin.')->prefix('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class)->parameters([
        'posts' => 'post:slug'
    ]);

    Route::resource('categories', CategoryController::class)->parameters([
        'categories' => 'category:slug'
    ])->except([
        'show', 'create', 'edit'
    ]);
    Route::resource('tags', TagController::class)->parameters([
        'tags' => 'tag:slug'
    ])->except([
        'show', 'create', 'edit'
    ]);
});

require __DIR__ . '/auth.php';
