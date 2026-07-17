<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\NewsController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
Route::get('/team', fn() => view('team'))->name('team');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/ships', [ShipController::class, 'index'])->name('ships.index');
    Route::get('/ships/create', [ShipController::class, 'create'])->name('ships.create');
    Route::post('/ships', [ShipController::class, 'store'])->name('ships.store');
    Route::get('/ships/{id}/edit', [ShipController::class, 'edit'])->name('ships.edit');
    Route::patch('/ships/{id}', [ShipController::class, 'update'])->name('ships.update');
    Route::delete('/ships/{id}', [ShipController::class, 'destroy'])->name('ships.destroy');

    Route::get('/work-orders', [WorkOrderController::class, 'index'])->name('work-orders.index');
    Route::get('/work-orders/create', [WorkOrderController::class, 'create'])->name('work-orders.create');
    Route::post('/work-orders', [WorkOrderController::class, 'store'])->name('work-orders.store');

    Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
    Route::patch('/workers/{id}', [WorkerController::class, 'update'])->name('workers.update');
    Route::delete('/workers/{id}', [WorkerController::class, 'destroy'])->name('workers.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

    Route::get('/news', [NewsAdminController::class, 'index'])->name('news.index');
    Route::get('/news/create', [NewsAdminController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsAdminController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [NewsAdminController::class, 'edit'])->name('news.edit');
    Route::patch('/news/{id}', [NewsAdminController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsAdminController::class, 'destroy'])->name('news.destroy');
});

require __DIR__.'/auth.php';