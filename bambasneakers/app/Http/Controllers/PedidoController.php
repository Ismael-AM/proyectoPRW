<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CarritoController;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\Carrito;
use App\Models\BD;

use PDO;

class PedidoController extends Controller{
    public static function realizarPedido(){
        session_start();
        $carrito = $_SESSION['carrito'] ?? [];

        if(empty($carrito)){
            return redirect('/carrito')->with(['carrito' => $carrito]);
        }
        $datos = Carrito::getDatosZapatillasCarrito($carrito);

        $tokenValido = Usuario::comprobarToken();

        if (!$tokenValido) {
            UsuarioController::logout();
            return redirect('/login')->with(['mensaje' => 'Token expirado, por favor inicia sesión','tipo' => 'danger']);
        }else{
            return view('pedido', ['carrito' => $carrito], ['datos' => $datos]);
        }
    }

    public static function confirmarPedido(Request $request){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        $carrito = $_SESSION['carrito'] ?? [];
        $tokenValido = Usuario::comprobarToken();

        if (!$tokenValido) {
            Usuario::logout();
            return response()->json(['token' => false]);        
        }else{
            if (empty($carrito)) {
                return view('carrito', ['carrito' => $carrito]);
            }
        }

        $bd = new BD();
        $conexion = $bd->conectar();

        $carrito = $_SESSION['carrito'] ?? [];
        $precioFinal = CarritoController::getPrecioTotal($carrito);
        
        $pedido = Pedido::storePedido($carrito, $precioFinal);
        
        if($pedido){
            CarritoController::vaciarCarrito();
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
        }
    }
}