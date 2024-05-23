<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuarryController;
use App\Http\Controllers\VehicleController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Order Route
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order', 'index')->name('order');
        Route::post('/order/add', 'add')->name('order.add');
        Route::post('/order/delete/{id}', 'delete')->name('order.delete');
    });
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

    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');

});

Route::middleware('check.role:2')->group(function () {
    //
});

Route::middleware('check.role:3')->group(function () {
    //
});
