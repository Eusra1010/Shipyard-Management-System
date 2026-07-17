<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\BerthController;
use App\Http\Controllers\NewsController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
Route::get('/team', fn() => view('team'))->name('team');
Route::get('/process', fn() => view('process'))->name('process');
Route::get('/facility', fn() => view('facility'))->name('facility');
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
    Route::get('/work-orders/{id}', [WorkOrderController::class, 'show'])->name('work-orders.show');
    Route::get('/work-orders/{id}/edit', [WorkOrderController::class, 'edit'])->name('work-orders.edit');
    Route::patch('/work-orders/{id}', [WorkOrderController::class, 'update'])->name('work-orders.update');
    Route::patch('/work-orders/{id}/status', [WorkOrderController::class, 'updateStatus'])->name('work-orders.status');

    Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
    Route::patch('/workers/{id}', [WorkerController::class, 'update'])->name('workers.update');
    Route::delete('/workers/{id}', [WorkerController::class, 'destroy'])->name('workers.destroy');

    Route::get('/berths', [BerthController::class, 'index'])->name('berths.index');
    Route::patch('/berths/{id}/assign', [BerthController::class, 'assign'])->name('berths.assign');
    Route::patch('/berths/{id}/release', [BerthController::class, 'release'])->name('berths.release');

    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::patch('/materials/{id}', [MaterialController::class, 'update'])->name('materials.update');
    Route::patch('/materials/{id}/restock', [MaterialController::class, 'restock'])->name('materials.restock');
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
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