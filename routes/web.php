<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\HistoryTitularController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Clients
Route::middleware(['auth', 'verified'])->group(function(){
    Route::resource('clients', ClientController::class);
    Route::get('getOccupations', [OccupationController::class, 'getOccupations']);
    Route::get('getAssociateds', [ClientController::class, 'getAssociateds']);
});
//Contracts
Route::middleware(['auth', 'verified'])->group(function(){
    Route::resource('contracts', ContractController::class);
    Route::post('contracts/{id}/change-contract-state', [ContractController::class, 'changeContractState']);
});
//Zones
Route::resource('zones', ZoneController::class)->middleware(['auth', 'verified']);
Route::get('/getZones', [ZoneController::class, 'getZones'])->middleware(['auth', 'verified']);
//Services
Route::resource('services', ServiceController::class)->middleware(['auth', 'verified']);
Route::get('/getServices', [ServiceController::class, 'getServices'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
