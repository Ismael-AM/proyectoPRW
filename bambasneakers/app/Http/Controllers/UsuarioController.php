<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Token_Usuario;
use App\Http\Controllers\CarritoController;
use App\Models\BD;

use PDO;

class UsuarioController extends Controller
{
    public function loginGoogle(){
        return Usuario::loginGoogle();  
        return redirect('/');
    }

    public static function comprobarToken(){
        $token = Usuario::comprobarToken();
        if($token){
            return true;
        } else {
            return false;
        }
    }
    
    public function confirmarLogin(Request $request){
        if (!$request->has('correo') || !$request->has('contraseña')) {
            return redirect('/registro')->with(['mensaje' => 'Ambos campos son obligatorios', 'tipo' => 'error']);
        }

        $usuario = Usuario::login($request);

        if($usuario){
            return redirect('/');
        }else{
            return redirect('/login')->with(['mensaje' => 'Credenciales incorrectas', 'tipo' => 'error']);
        }
    }
    
    public function registrarUsuario(Request $request){
        if (!$request->has('nombre') || !$request->has('correo') || !$request->has('contraseña') || !$request->has('genero')) {
            return redirect('/registro')->with(['mensaje' => 'Todos los campos son obligatorios', 'tipo' => 'error']);
        }

        if (!filter_var($request->input('correo'), FILTER_VALIDATE_EMAIL)) {
            return redirect('/registro')->with(['mensaje' => 'Correo electrónico no válido', 'tipo' => 'error']);
        }

        $bd = new BD();
        $conexion = $bd->conectar();

        $query = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $query->execute([
            'correo' => $request->input('correo'),
        ]);

        $usuario = $query->fetch(PDO::FETCH_ASSOC);

        $bd->cerrarConexion();
        if ($usuario) {
            return redirect('/registro')->with(['mensaje' => 'El correo electrónico ya está registrado', 'tipo' => 'error']);
        }

        $resultado = Usuario::storeUsuario($request);

        if ($resultado) {
            Usuario::login($request);
            return redirect('/')->with(['mensaje' => 'Usuario registrado correctamente', 'tipo' => 'success']);
        } else {
            return redirect('/registro')->with(['mensaje' => 'Error al registrar usuario', 'tipo' => 'error']);
        }
    }

    public static function logout(){
        Usuario::logout();
        return redirect('/');
    }

    public static function estadoSesion(){
        session_start();
    
        if (!isset($_SESSION['iniciada'])) {
            return response()->json(['iniciada' => false]);
        }
    
        return response()->json(['iniciada' => true]);
    }
}