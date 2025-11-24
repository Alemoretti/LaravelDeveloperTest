<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\PlanetController;
use Illuminate\Support\Facades\Route;

// Home page - redirect to characters
Route::get('/', function () {
    return redirect()->route('characters.index');
});

// Character routes
Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/characters/{character}', [CharacterController::class, 'show'])->name('characters.show');

// Planet routes
Route::get('/planets', [PlanetController::class, 'index'])->name('planets.index');
Route::get('/planets/{planet}', [PlanetController::class, 'show'])->name('planets.show');
