<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    // Songs
    Route::prefix('songs')->name('songs.')->group(function () {
        Route::get('/', \App\Livewire\Songs\SongIndex::class)->name('index');
        Route::get('/create', \App\Livewire\Songs\SongForm::class)->name('create');
        Route::get('/{song}', \App\Livewire\Songs\SongShow::class)->name('show');
        Route::get('/{song}/edit', \App\Livewire\Songs\SongForm::class)->name('edit');
    });

    // Setlists
    Route::prefix('setlists')->name('setlists.')->group(function () {
        Route::get('/', \App\Livewire\Setlists\SetlistIndex::class)->name('index');
        Route::get('/create', \App\Livewire\Setlists\SetlistForm::class)->name('create');
        Route::get('/{setlist}', \App\Livewire\Setlists\SetlistShow::class)->name('show');
        Route::get('/{setlist}/edit', \App\Livewire\Setlists\SetlistForm::class)->name('edit');
    });

    // Gigs
    Route::prefix('gigs')->name('gigs.')->group(function () {
        Route::get('/', \App\Livewire\Gigs\GigIndex::class)->name('index');
        Route::get('/create', \App\Livewire\Gigs\GigForm::class)->name('create');
        Route::get('/{gig}', \App\Livewire\Gigs\GigShow::class)->name('show');
        Route::get('/{gig}/edit', \App\Livewire\Gigs\GigForm::class)->name('edit');
    });

    // Gig Mode
    Route::get('/gigs/{gig}/mode', \App\Livewire\GigMode\GigMode::class)->name('gig-mode');

    // Rehearsals
    Route::prefix('rehearsals')->name('rehearsals.')->group(function () {
        Route::get('/', \App\Livewire\Rehearsals\RehearsalIndex::class)->name('index');
        Route::get('/create', \App\Livewire\Rehearsals\RehearsalForm::class)->name('create');
        Route::get('/{rehearsal}', \App\Livewire\Rehearsals\RehearsalShow::class)->name('show');
        Route::get('/{rehearsal}/edit', \App\Livewire\Rehearsals\RehearsalForm::class)->name('edit');
    });

    // Performances
    Route::prefix('performances')->name('performances.')->group(function () {
        Route::get('/', \App\Livewire\Performances\PerformanceIndex::class)->name('index');
    });

    // Analytics
    Route::get('/analytics', \App\Livewire\Analytics\AnalyticsDashboard::class)->name('analytics');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', \App\Livewire\Settings\Profile::class)->name('profile');
    });
});
