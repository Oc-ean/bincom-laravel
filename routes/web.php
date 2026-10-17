<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PollingUnitController;
use App\Http\Controllers\LgaSumController;
use App\Http\Controllers\AddPollingUnitController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/debug-build', function () {
    $m = public_path('build/manifest.json');
    return response()->json([
        'manifest_exists' => file_exists($m),
        'manifest' => file_exists($m) ? json_decode(file_get_contents($m), true) : null,
        'css_exists' => file_exists(public_path('build/assets/app-DJbdyHn8.css')),
        'build_dir_listing' => is_dir(public_path('build')) ? scandir(public_path('build')) : [],
        'assets_dir_listing' => is_dir(public_path('build/assets')) ? scandir(public_path('build/assets')) : [],
    ]);
});

Route::get('/polling-unit', [PollingUnitController::class, 'index'])->name('polling-unit');
Route::get('/polling-unit/wards/{lga_id}', [PollingUnitController::class, 'wards']);
Route::get('/polling-unit/pus/{ward_id}', [PollingUnitController::class, 'pollingUnits']);
Route::get('/polling-unit/result/{uniqueid}', [PollingUnitController::class, 'result']);

Route::get('/lga-sum', [LgaSumController::class, 'index'])->name('lga-sum');
Route::get('/lga-sum/data/{lga_id}', [LgaSumController::class, 'data']);

Route::get('/add-polling-unit', [AddPollingUnitController::class, 'index'])->name('add-polling-unit');
Route::post('/add-polling-unit', [AddPollingUnitController::class, 'store'])->name('add-polling-unit.store');