<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Encriptar;
use Inertia\Inertia;
use App\Http\Controllers\HistoryController;


Route::get('/', function(){
    return View('Prueba');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/historial', [HistoryController::class, 'getHistory']);

//encriptar
Route::post('/encriptar', [Encriptar::class, 'encriptar']);
Route::post('/desencriptar', [Encriptar::class, 'desencriptar']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
