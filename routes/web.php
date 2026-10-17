<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PollingUnitController;
use App\Http\Controllers\LgaSumController;
use App\Http\Controllers\AddPollingUnitController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/polling-unit', [PollingUnitController::class, 'index'])->name('polling-unit');
Route::get('/polling-unit/wards/{lga_id}', [PollingUnitController::class, 'wards']);
Route::get('/polling-unit/pus/{ward_id}', [PollingUnitController::class, 'pollingUnits']);
Route::get('/polling-unit/result/{uniqueid}', [PollingUnitController::class, 'result']);

Route::get('/lga-sum', [LgaSumController::class, 'index'])->name('lga-sum');
Route::get('/lga-sum/data/{lga_id}', [LgaSumController::class, 'data']);

Route::get('/add-polling-unit', [AddPollingUnitController::class, 'index'])->name('add-polling-unit');
Route::post('/add-polling-unit', [AddPollingUnitController::class, 'store'])->name('add-polling-unit.store');