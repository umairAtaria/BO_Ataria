<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisplayDataController;
use App\Http\Controllers\Mt5ConnectionController;
use App\Http\Controllers\SwapUpdateController;
use App\Http\Controllers\TranslationsController;
use App\Models\Translations;
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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/settings', [Mt5ConnectionController::class, 'mt5_connect'])->name('mt5_connection');
    Route::post('/settings/save', [Mt5ConnectionController::class, 'save_connection'])->name('save_connection');
    Route::POST('/settings/con', [Mt5ConnectionController::class, 'con'])->name('con');

    //Symbols
    Route::get('/symbols', [DisplayDataController::class, 'index'])->name('symbols');
    Route::get('/show/ajax', [DisplayDataController::class, 'newfun'])->name('symbols_ajax');
    Route::post('/swap/update/single',[DisplayDataController::class, 'update'])->name('swap.update');

    
    Route::get('/swap/update', [SwapUpdateController::class, 'index'])->name('swap_update_index');
    Route::post('/swap/import', [SwapUpdateController::class, 'import'])->name('swap_import');
    Route::get('/show/symbols/sync_api', [DisplayDataController::class, 'sync_api'])->name('commission.sync');

    // Groups
    Route::get('/group/swap', [SwapUpdateController::class, 'group'])->name('group_swap');
    Route::post('/group/swap/update', [SwapUpdateController::class, 'group_update'])->name('group_update_index');
    Route::get('/group/symbols/sync_api', [SwapUpdateController::class, 'sync_groups'])->name('groups.sync');
    Route::get('/group/ajax', [SwapUpdateController::class, 'displayData'])->name('groups_ajax');

    //Translations
    Route::get('/translations', [TranslationsController::class, 'translations'])->name('translations');
    Route::get('/translations/ajax',[TranslationsController::class, 'ajax'])->name('translations_ajax');
    Route::post('/translations/save',[TranslationsController::class, 'save'])->name('translations_save');
    Route::post('/translations/delete', [TranslationsController::class, 'destroy'])->name('translations_delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
