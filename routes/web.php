<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\Api\VideoResetController;
use App\Http\Controllers\Api\GetPlaylistController;
use App\Http\Controllers\Api\ShowPlaylistController;
use App\Http\Controllers\Api\IndexPlaylistController;
use App\Http\Controllers\Api\StorePlaylistController;
use App\Http\Controllers\Api\VideoProgressController;
use App\Http\Controllers\Api\GetPlaylistInfoController;
use App\Http\Controllers\Api\UpdateVideoTimeController;
use App\Http\Controllers\Api\VideoCompletionController;
use App\Http\Controllers\Api\GetPlaylistVideoController;
use App\Http\Controllers\Api\ToggleVideoCompletionController;

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

Route::middleware('auth')->group(function () {
    // Playlists API Routes
    Route::get('/api/playlists/info', GetPlaylistInfoController::class);
    Route::post('/api/playlists', StorePlaylistController::class);
    Route::get('/api/playlists', GetPlaylistController::class);
    Route::get('/api/playlist/{playlist:playlist_id}/videos', GetPlaylistVideoController::class);

    // update the time for videos
    Route::post('/api/update-time', UpdateVideoTimeController::class);

    // Video Progress API Routes
    Route::post('/api/videos/complete', [VideoProgressController::class, 'complete']);
    Route::post('/api/videos/reset', [VideoProgressController::class, 'reset']);

    // Playlists Routes
    Route::get('/playlists', IndexPlaylistController::class)->name('playlists.index');
    Route::resource('/playlists', PlaylistController::class)->only('create');
    Route::get('/playlist', ShowPlaylistController::class)->name('playlists.show');
    Route::get('/watch', [VideoController::class, 'show'])->name('videos.show');
    Route::delete('/playlist/{playlist:playlist_id}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
});

require __DIR__ . '/auth.php';
