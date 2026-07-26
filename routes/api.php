<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Song;
use App\Models\Setlist;
use App\Models\Performance;

// API routes are loaded by the RouteServiceProvider and all of them will
// be assigned to the "api" middleware group.

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Songs API
    Route::prefix('songs')->group(function () {
        Route::get('/', function (Request $request) {
            return Song::with(['tags', 'links'])->paginate(20);
        });
        
        Route::get('/{song}', function (Song $song) {
            return $song->load(['tags', 'links']);
        });
    });

    // Setlists API
    Route::prefix('setlists')->group(function () {
        Route::get('/', function (Request $request) {
            return Setlist::withCount('songs')->paginate(20);
        });
        
        Route::get('/{setlist}', function (Setlist $setlist) {
            return $setlist->load(['songs' => function ($q) {
                $q->orderBy('setlist_song.position');
            }]);
        });
    });
    
    // Performance API
    Route::get('/performances', function (Request $request) {
        return Performance::with(['song', 'setlist'])->orderByDesc('performed_at')->paginate(20);
    });
});
