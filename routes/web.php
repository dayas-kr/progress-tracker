<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\StorePlaylistController;
use App\Http\Controllers\Api\GetPlaylistInfoController;
use App\Http\Controllers\FetchPlaylistVideoController;

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
    Route::get('/api/playlist-videos', FetchPlaylistVideoController::class);
    Route::post('/api/playlists', StorePlaylistController::class);

    // Playlists Routes
    Route::resource('/playlists', PlaylistController::class)->only('index', 'create');
    Route::get('/playlist', [PlaylistController::class, 'show'])->name('playlists.show');
    Route::get('/watch', [VideoController::class, 'show'])->name('videos.show');
    Route::delete('/playlist/{playlist:playlist_id}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
});

require __DIR__ . '/auth.php';
