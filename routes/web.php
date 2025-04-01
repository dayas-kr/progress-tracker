<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\Api\ShowVideoController;
use App\Http\Controllers\Api\GetPlaylistController;
use App\Http\Controllers\Api\ShowPlaylistController;
use App\Http\Controllers\Api\VideoSessionController;
use App\Http\Controllers\Api\IndexPlaylistController;
use App\Http\Controllers\Api\StorePlaylistController;
use App\Http\Controllers\Api\VideoProgressController;
use App\Http\Controllers\Api\GetPlaylistInfoController;
use App\Http\Controllers\Api\UpdateVideoTimeController;
use App\Http\Controllers\Api\GetPlaylistVideoController;
use App\Http\Controllers\Api\PlaylistProgressController;

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

// Playlists Routes
Route::middleware('auth')->group(function () {
    Route::get('/playlists', IndexPlaylistController::class)->name('playlists.index');
    Route::resource('/playlists', PlaylistController::class)->only('create');
    Route::get('/playlist', ShowPlaylistController::class)->name('playlists.show');
    Route::get('/watch', ShowVideoController::class)->name('videos.show');
    Route::delete('/playlist/{playlist:playlist_id}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
});

// API Routes
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/playlists/info', GetPlaylistInfoController::class);
    Route::post('/playlists', StorePlaylistController::class);
    Route::get('/playlists', GetPlaylistController::class);
    Route::get('/playlist/{playlist:playlist_id}/videos', GetPlaylistVideoController::class);

    // update the time for videos
    Route::post('/update-time', UpdateVideoTimeController::class);

    // Video Progress
    Route::post('/videos/complete', [VideoProgressController::class, 'complete']);
    Route::post('/videos/reset', [VideoProgressController::class, 'reset']);

    // Playlist Progress
    Route::post('/playlists/complete', [PlaylistProgressController::class, 'complete']);
    Route::post('/playlists/reset', [PlaylistProgressController::class, 'reset']);

    // Video Playback Options
    Route::post('/video-playback-options', VideoSessionController::class)->name('video-playback-options');
});

require __DIR__ . '/auth.php';
