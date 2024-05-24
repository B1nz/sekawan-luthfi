<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\QuarryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\OrderRequestController;
use App\Http\Controllers\VehicleHistoryController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Order Route
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order', 'index')->name('order');
        Route::post('/order/add', 'add')->name('order.add');
        Route::post('/order/delete/{id}', 'delete')->name('order.delete');
    });

    // routes/web.php
    Route::post('/export/excel', [ExportController::class, 'exportToExcel'])->name('export.excel');
});

Route::middleware('check.role:1')->group(function () {

    // Driver Route
    Route::controller(DriverController::class)->group(function () {
        Route::get('/driver', 'index')->name('driver');
        Route::post('/driver/add', 'add')->name('driver.add');
        Route::post('/driver/edit/{id}', 'edit')->name('driver.edit');
        Route::post('/driver/delete/{id}', 'delete')->name('driver.delete');
    });

    // Quarry Route
    Route::controller(QuarryController::class)->group(function () {
        Route::get('/quarry', 'index')->name('quarry');
        Route::post('/quarry/add', 'add')->name('quarry.add');
        Route::post('/quarry/edit/{id}', 'edit')->name('quarry.edit');
        Route::post('/quarry/delete/{id}', 'delete')->name('quarry.delete');
    });

    // Vehicle Route
    Route::controller(VehicleController::class)->group(function () {
        Route::get('/vehicle', 'index')->name('vehicle');
        Route::post('/vehicle/add', 'add')->name('vehicle.add');
        Route::post('/vehicle/edit/{id}', 'edit')->name('vehicle.edit');
        Route::post('/vehicle/delete/{id}', 'delete')->name('vehicle.delete');
        Route::get('/vehicle/history/{id}', 'getHistory')->name('vehicle.history');
    });

    // User Route
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('user');
        Route::post('/user/edit/{id}', 'edit')->name('user.edit');
        Route::post('/user/delete/{id}', 'delete')->name('user.delete');
        Route::get('/user/roles', 'getRoles')->name('user.getRole');
    });

    // Office Route
    Route::controller(OfficeController::class)->group(function () {
        Route::get('/office', 'index')->name('office');
        Route::post('/office/add', 'add')->name('office.add');
        Route::post('/office/edit/{id}', 'edit')->name('office.edit');
        Route::post('/office/delete/{id}', 'delete')->name('office.delete');
    });

    Route::get('/vehicle/history/{id}', [VehicleHistoryController::class, 'getVehicleHistory'])->name('vehicle.history');

    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');

});

Route::middleware(['check.multiple.role:1,2'])->group(function () {
    // Order Request Route
    Route::controller(OrderRequestController::class)->group(function () {
        Route::get('/orreq', 'index')->name('orreq');
        Route::post('/orreq/approve/{id}', 'approve')->name('orreq.approve');
        Route::post('/orreq/reject/{id}', 'reject')->name('orreq.reject');
    });
});

Route::middleware('check.role:2')->group(function () {
    Route::post('/order/done/{id}', [OrderController::class, 'done'])->name('order.done');
});
