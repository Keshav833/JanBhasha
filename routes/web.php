<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlossaryController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────
// Public routes
// ──────────────────────────────────────────

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Tour complete
Route::post('/tour/complete', function () {
    auth()->user()->update(['tour_completed' => true]);
    return response()->json(['ok' => true]);
})->middleware('auth')->name('tour.complete');

// ──────────────────────────────────────────
// Authenticated routes (Breeze session auth)
// ──────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Translations
    Route::resource('translations', TranslationController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // Glossary
    Route::resource('glossary', GlossaryController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // ──────────────────────────────────────────
    // Super-admin panel
    // ──────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {

        // Admin Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Organisations (CRUD)
        Route::resource('organisations', OrganisationController::class);
        Route::post(
            'organisations/{organisation}/regenerate-key',
            [OrganisationController::class, 'regenerateApiKey']
        )->name('organisations.regenerate-key');

        // Users (CRUD)
        Route::get('/users',                [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create',         [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',               [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit',    [AdminController::class, 'editUser'])->name('users.edit');
        Route::patch('/users/{user}',       [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}',      [AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});

require __DIR__ . '/auth.php';
