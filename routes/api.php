<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Song;
use App\Models\Setlist;
use App\Models\Performance;
use App\Models\Gig;
use App\Models\Rehearsal;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('notifications');
    });

    // Songs API
    Route::prefix('songs')->group(function () {
        Route::get('/', function (Request $request) {
            return Song::with(['tags', 'links', 'attachments'])->paginate(20);
        });

        Route::get('/{song}', function (Song $song) {
            return $song->load(['tags', 'links', 'attachments', 'checklists', 'performances']);
        });

        Route::post('/', function (Request $request) {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'artist' => 'nullable|string|max:255',
                'genre' => 'nullable|string|max:100',
                'key' => 'nullable|string|max:10',
                'bpm' => 'nullable|integer|min:20|max:300',
                'duration' => 'nullable|integer|min:1',
            ]);
            $data['created_by'] = $request->user()->id;
            return Song::create($data);
        });

        Route::put('/{song}', function (Request $request, Song $song) {
            $data = $request->validate([
                'title' => 'string|max:255',
                'artist' => 'nullable|string|max:255',
                'genre' => 'nullable|string|max:100',
                'key' => 'nullable|string|max:10',
                'bpm' => 'nullable|integer|min:20|max:300',
                'duration' => 'nullable|integer|min:1',
                'is_favorite' => 'boolean',
            ]);
            $song->update($data);
            return $song;
        });

        Route::delete('/{song}', function (Song $song) {
            $song->delete();
            return response()->json(['message' => 'Deleted']);
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

        Route::post('/', function (Request $request) {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string|in:rehearsal,performance',
                'scheduled_at' => 'nullable|date',
                'venue' => 'nullable|string|max:255',
            ]);
            $data['created_by'] = $request->user()->id;
            return Setlist::create($data);
        });

        Route::delete('/{setlist}', function (Setlist $setlist) {
            $setlist->delete();
            return response()->json(['message' => 'Deleted']);
        });
    });

    // Gigs API
    Route::prefix('gigs')->group(function () {
        Route::get('/', function () {
            return Gig::with(['setlist', 'members'])->orderByDesc('date')->paginate(20);
        });

        Route::get('/{gig}', function (Gig $gig) {
            return $gig->load(['setlist.songs', 'members', 'requests', 'expenses']);
        });

        Route::post('/', function (Request $request) {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'venue' => 'required|string|max:255',
                'date' => 'required|date',
                'payment' => 'nullable|numeric',
                'tips' => 'nullable|numeric',
                'status' => 'nullable|string',
            ]);
            $data['created_by'] = $request->user()->id;
            return Gig::create($data);
        });
    });

    // Rehearsals API
    Route::prefix('rehearsals')->group(function () {
        Route::get('/', function () {
            return Rehearsal::with(['setlist', 'members'])->orderByDesc('date')->paginate(20);
        });

        Route::get('/{rehearsal}', function (Rehearsal $rehearsal) {
            return $rehearsal->load(['setlist.songs', 'members', 'checklists']);
        });
    });

    // Performances API
    Route::get('/performances', function (Request $request) {
        return Performance::with(['song', 'setlist', 'gig'])->orderByDesc('performed_at')->paginate(20);
    });

    // Dashboard Stats API
    Route::get('/stats', function () {
        return [
            'total_songs' => Song::count(),
            'total_gigs' => Gig::count(),
            'total_performances' => Performance::count(),
            'total_setlists' => Setlist::count(),
            'upcoming_gigs' => Gig::upcoming()->count(),
            'favorite_songs' => Song::favorites()->count(),
        ];
    });
});
