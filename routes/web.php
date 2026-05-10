<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaperSearchController;
use App\Http\Controllers\AiWritingController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\GoogleAuthController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Paper Search Routes
    Route::get('/paper-search', [PaperSearchController::class, 'index'])->name('paper.index');
    Route::post('/paper-search/search', [PaperSearchController::class, 'search'])->name('paper.search');
    Route::get('/paper-search/paper/{paperId}', [PaperSearchController::class, 'getPaperDetails'])->name('paper.details');
    Route::get('/saved-papers', [PaperSearchController::class, 'saved'])->name('paper.saved');
    Route::post('/paper-search/toggle-save', [PaperSearchController::class, 'toggleSave'])->name('paper.toggle-save');
    Route::post('/paper-search/check-saved', [PaperSearchController::class, 'checkSaved'])->name('paper.check-saved');
    
    // AI Writing Assistant Routes
    Route::get('/ai-writing', [AiWritingController::class, 'index'])->name('ai-writing.index');
    Route::post('/ai-writing/generate', [AiWritingController::class, 'generate'])->name('ai-writing.generate');
    Route::post('/ai-writing/improve', [AiWritingController::class, 'improve'])->name('ai-writing.improve');
    Route::post('/ai-writing/correct-typo', [AiWritingController::class, 'correctTypo'])->name('ai-writing.correct-typo');
    Route::post('/ai-writing/find-gap', [AiWritingController::class, 'findGap'])->name('ai-writing.find-gap');
    Route::get('/ai-writing/history', [AiWritingController::class, 'history'])->name('ai-writing.history');
    
    // Thesis Management Routes
    Route::get('/thesis', [ThesisController::class, 'index'])->name('thesis.index');
    Route::get('/thesis/create', [ThesisController::class, 'create'])->name('thesis.create');
    Route::post('/thesis', [ThesisController::class, 'store'])->name('thesis.store');
    Route::get('/thesis/{thesis}', [ThesisController::class, 'show'])->name('thesis.show');
    Route::get('/thesis/{thesis}/edit', [ThesisController::class, 'edit'])->name('thesis.edit');
    Route::patch('/thesis/{thesis}', [ThesisController::class, 'update'])->name('thesis.update');
    Route::delete('/thesis/{thesis}', [ThesisController::class, 'destroy'])->name('thesis.destroy');
    Route::post('/thesis/{thesis}/chapter-progress', [ThesisController::class, 'updateChapterProgress'])->name('thesis.chapter-progress');
    Route::get('/thesis/{thesis}/export/{format?}', [ThesisController::class, 'export'])->name('thesis.export');
    
    // No document management routes — this app does not store any files or thesis data
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

require __DIR__.'/auth.php';
