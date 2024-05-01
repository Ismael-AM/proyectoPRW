<?php

use Illuminate\Support\Facades\Route;
use App\Models\Usuario;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ZapatillaController;
use App\Http\Controllers\CarritoController;

Route::prefix('bambas')->group(function () {
    Route::get('/zapatillas', [ZapatillaController::class, 'getZapatillas']);
    Route::get('/zapatillas/search', [ZapatillaController::class, 'getZapatillasBySearch']);
    Route::get('/zapatillas/{id}', [ZapatillaController::class, 'getZapatillaById']);
    Route::get('/zapatillas/marca/{marca}', [ZapatillaController::class, 'getZapatillasByMarca']);
    Route::get('/zapatillas/categoria/{categoria}', [ZapatillaController::class, 'getZapatillasByCategoria']);
});

?>