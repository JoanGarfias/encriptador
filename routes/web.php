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
Route::get('/history', [HistoryController::class, 'getHistory'])->name('history.index');
// Rutas para descargar desde la base de datos (HistoryController mantiene esas rutas)
Route::get('/history/download/llaves/{id}.key', [HistoryController::class, 'downloadKey'])->name('history.download.key');
Route::get('/history/download/encriptado/{id}.txt', [HistoryController::class, 'downloadContent'])->name('history.download.content');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



//encriptar
Route::post('/encriptar', [Encriptar::class, 'encriptarArchivo'])->name('encriptar')->middleware(['auth']);
Route::post('/desencriptar', [Encriptar::class, 'desencriptarArchivo'])->name('desencriptar')->middleware(['auth']);
// Ruta que recibe upload (encriptado + key) y devuelve el archivo desencriptado como descarga
Route::post('/desencriptar/download', [Encriptar::class, 'downloadDecryptedFromUpload'])->name('desencriptar.download')->middleware(['auth']);
Route::get('/downloads/{filename}', [Encriptar::class, 'downloadFile'])->name('descargar')->middleware(['auth']);
// Nuevas rutas para descargar desde el modelo
Route::get('/download/content/{id}', [Encriptar::class, 'downloadContent'])->name('download.content')->middleware(['auth']);
Route::get('/download/key/{id}', [Encriptar::class, 'downloadKey'])->name('download.key')->middleware(['auth']);
