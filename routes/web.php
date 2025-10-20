<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Encriptar;
use Inertia\Inertia;
use App\Http\Controllers\HistoryController;



Route::get('/', function(){
    return Inertia::render('Welcome');
});

Route::get('/perfil', function () {
    return Inertia::render('Profile');
})->middleware(['auth'])->name('profile');

Route::get('/test', function(){
    return View('Prueba2');
});



Route::get('/history', [HistoryController::class, 'getHistory'])->name('history.index');
Route::get('/history/download/llaves/{id}.key', [HistoryController::class, 'downloadKey'])->name('history.download');
Route::get('/history/download/encriptado/{id}.txt', [HistoryController::class, 'downloadContent'])->name('history.download');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



//encriptar
Route::post('/encriptar', [Encriptar::class, 'encriptarArchivo'])->name('encriptar');
Route::post('/desencriptar', [Encriptar::class, 'desencriptarArchivo'])->name('desencriptar');
Route::get('/downloads/{filename}', [Encriptar::class, 'downloadFile'])->name('descargar');
