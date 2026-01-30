<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppcaptionController;

use Illuminate\Support\Facades\Route;

    /* language */
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['hu', 'en', 'de'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');    
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/appcaptions/exportlangfiles', [App\Http\Controllers\AppcaptionController::class, 'exportTranslationsToLangFiles'])
        ->name('appcaptions.exportlangfiles')
        ->middleware('auth');
    Route::get('/appcaptions/searchtranslations', [App\Http\Controllers\AppcaptionController::class, 'searchtranslations'])
        ->name('appcaptions.searchtranslations');

    Route::resource('appcaptions', AppcaptionController::class);
    
});

Route::fallback(function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    abort(404);
});

require __DIR__.'/auth.php';
