<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ZapatillaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CarritoController;


Route::get('/', function () {
    return view('home')->with('novedades', ZapatillaController::getNovedades());
})->name('home');

Route::get('/login', function () {
    return view('usuarios.login');
});

Route::get('logout', [UsuarioController::class, 'logout']);

Route::get('/registro', function () {
    return view('usuarios.registrar');
});

Route::post('/confirmarLogin', [UsuarioController::class, 'confirmarLogin']);
Route::post('/confirmarRegistro', [UsuarioController::class, 'registrarUsuario']);

Route::get('/loginGoogle', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/googleCallback', [UsuarioController::class, 'loginGoogle']);

Route::get('/sneakers', [ZapatillaController::class, 'index']);
Route::get('/sneakers/search', [ZapatillaController::class, 'search']);
Route::get('/sneaker/{id}', [ZapatillaController::class, 'view']);

Route::post('/añadirAlCarrito/{id}', [ZapatillaController::class, 'añadirAlCarrito']);
Route::post('/eliminarDelCarrito/{id}', [ZapatillaController::class, 'eliminarDelCarrito']);
Route::get('/vaciarCarrito', [CarritoController::class, 'vaciarCarrito']);
Route::post('/actualizarCarrito/{id}', [CarritoController::class, 'actualizarCarrito']);

Route::get('/carrito', [CarritoController::class, 'getCarrito']);
Route::get('/realizarPedido', [PedidoController::class, 'realizarPedido']);
Route::post('/confirmarPedido', [PedidoController::class, 'confirmarPedido']);
Route::get('/estado-sesion', [UsuarioController::class, 'estadoSesion']);

Route::get('/error', function () {
    return view('errorJS');
})->name('errorJS');