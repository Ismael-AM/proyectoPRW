<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UsuarioController;
use App\Models\Carrito;
use App\Models\Usuario;
use App\Models\BD;

use PDO;

class CarritoController extends Controller
{
    public function cargarCarrito(){
        $carrito = Carrito::cargarCarrito();
        return response()->json(['carrito' => $carrito]);
    }

    public function actualizarCarrito($idzapatilla, Request $request){
        $id_talla = $request->input('talla');
        $cantidad = $request->input('cantidad');

        if($id_talla == null){
            return response()->json(['success' => false]);
        }

        if($idzapatilla == null){
            return response()->json(['success' => false]);
        }

        $token = Usuario::comprobarToken();

        if(!$token){
            Usuario::logout();
            return response()->json(['token' => false]);
        }
    
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    
        $carrito = $_SESSION['carrito'] ?? [];
    
        $zapatillaExiste = false;
    
        foreach ($carrito as $pivote => $producto) {
            if ($producto['id_zapatilla'] == $idzapatilla && $producto['id_talla'] == $id_talla) {
                $carrito[$pivote]['cantidad'] = $cantidad;
                $zapatillaExiste = true;
                break;
            }
        }

        if(!$zapatillaExiste){
            return response()->json(['success' => false]);
        }
    
        $_SESSION['carrito'] = $carrito;
    
        return response()->json(['success' => true]);
    }
    

    public static function getCarrito(){
        session_start();
        $carrito = $_SESSION['carrito'] ?? [];
        if(!isset($_SESSION['iniciada'])){
            return redirect('/login')->with(['mensaje' => 'Sesión no iniciada','tipo' => 'danger']);        
        }
        $tokenValido = Usuario::comprobarToken();

        if (!$tokenValido) {
            Usuario::logout();
            return redirect('/login')->with(['mensaje' => 'Token expirado, por favor inicia sesión','tipo' => 'danger']);        
        }else{
            if (empty($carrito)) {
                return view('carrito', ['carrito' => $carrito]);
            }
        
            $datos = Carrito::getDatosZapatillasCarrito($carrito);
                        
            return view('carrito', ['carrito' => $carrito], ['datos' => $datos]);
        }
    }

    public static function vaciarCarrito(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        };
        $_SESSION['carrito'] = [];

        return redirect('/carrito');
    }

    public static function getPrecioTotal($carrito){
        $total = 0;

        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        return $total;
    }
}