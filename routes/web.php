<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Encriptar;
use App\Http\Controllers\Encriptar2;
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

/*
Route::get('/historial', [HistoryController::class, 'getHistory']);
*/

/*
Route::get('/history', [HistoryController::class, 'index'])->name('index');
Route::get('/history/{id}/download', [HistoryController::class, 'downloadKey'])->name('download.key');
*/

Route::get('/history', function () {
    return Inertia::render('Historial');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



//encriptar
Route::post('/encriptar', [Encriptar::class, 'encriptar']);
Route::post('/desencriptar', [Encriptar::class, 'desencriptar']);

Route::post('/encriptar2', [Encriptar2::class, 'encriptarArchivo']);
Route::post('/desencriptar2', [Encriptar2::class, 'desencriptarArchivo']);
Route::get('/downloads/{filename}', [Encriptar2::class, 'downloadFile'])->name('download.file');
