<?php

use App\Http\Controllers\AdditionalServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\HistoryTitularController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    $userName = Auth::user()->name;

    return view('dashboard', compact('userName'));
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
    Route::get('getContracts', [ContractController::class, 'getContracts']);
    Route::post('contracts/{id}/change-contract-state', [ContractController::class, 'changeContractState']);
});
//Debts
Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('debts/{id}', [DebtController::class, 'getAllDebts']);
    Route::get('debts/{id}/bill', [DebtController::class, 'generatePdfBill']);
});
//Payments
Route::middleware(['auth', 'verified'])->group(function(){
    Route::resource('payments', PaymentController::class);
    Route::get('payments/{id}/receipt', [PaymentController::class, 'generatePdfReceipt']);
    Route::post('payments/{id}/null-payment', [PaymentController::class, 'nullPayment']);
});
//Zones
Route::resource('zones', ZoneController::class)->middleware(['auth', 'verified']);
Route::get('/getZones', [ZoneController::class, 'getZones'])->middleware(['auth', 'verified']);
//Services
Route::resource('services', ServiceController::class)->middleware(['auth', 'verified']);
Route::get('/getServices', [ServiceController::class, 'getServices'])->middleware(['auth', 'verified']);
//Additional Services
Route::resource('additional-services', AdditionalServiceController::class)->middleware(['auth', 'verified']);
Route::get('/getAdditionalServices', [AdditionalServiceController::class, 'getAdditionalServices'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
