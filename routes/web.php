<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (SEMUA USER LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |==================== DASHBOARD ====================
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/user', [DashboardController::class, 'user'])
        ->name('dashboard.user');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware(AdminMiddleware::class)
        ->name('dashboard.admin');
    

    /*
    |==================== USER / PETUGAS ====================
    */
    Route::middleware(UserMiddleware::class)->group(function () {

        Route::get('/arsip/create', [ArsipController::class, 'create'])
            ->name('arsip.create');

        Route::post('/arsip', [ArsipController::class, 'store'])
            ->name('arsip.store');

        Route::get('/arsip/{arsip}/edit', [ArsipController::class, 'edit'])
            ->name('arsip.edit');

        Route::put('/arsip/{arsip}', [ArsipController::class, 'update'])
            ->name('arsip.update');

        Route::delete('/arsip/{arsip}', [ArsipController::class, 'destroy'])
            ->name('arsip.destroy');

        Route::get('/arsip/import', [ArsipController::class, 'showImportForm'])
            ->name('arsip.import.form');

        Route::post('/arsip/import', [ArsipController::class, 'handleImport'])
            ->name('arsip.import');

        Route::get('/arsip/search', [ArsipController::class, 'search'])
            ->name('arsip.search');

        Route::get('/arsip/export-csv', [ArsipController::class, 'exportCsv'])
            ->name('arsip.export.csv');
    });

    /*
    |==================== SEMUA USER LOGIN ====================
    */
    Route::get('/arsip', [ArsipController::class, 'index'])
        ->name('arsip.index');

    Route::get('/arsip/{arsip}/download', [ArsipController::class, 'download'])
        ->name('arsip.download');

    /*
    |==================== ADMIN ====================
    */
    Route::middleware(AdminMiddleware::class)->group(function () {

        Route::get('/arsip/verifikasi', [ArsipController::class, 'verifikasiIndex'])
            ->name('arsip.verifikasi');

        Route::put('/arsip/verifikasi/{arsip}', [ArsipController::class, 'verifikasiApprove'])
            ->name('arsip.verifikasi.approve');

         // manajemen user
    Route::get('/users', [UserController::class, 'index'])
        ->name('user.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('user.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('user.store');

    // EDIT
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('user.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('user.update');

    // HAPUS
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('user.destroy');
    });
});