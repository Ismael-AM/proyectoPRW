<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\BD;

use PDO;

class Usuario extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'apellidos', 'correo', 'contraseña', 'género', 'avatar', 'external_id', 'external_auth'];

    public static function loginGoogle(){
        $user = Socialite::driver('google')->user();

        $bd = new BD();
        $conexion = $bd->conectar();
 
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE external_id = :external_id AND external_auth = :external_auth");
        $query->execute([
            'external_id' => $user->id,
            'external_auth' => 'google'
        ]);

        $userExists = $query->fetch(PDO::FETCH_ASSOC);

        if ($userExists) {
            $queryToken = $conexion->prepare("SELECT * FROM token_usuario WHERE id_usuario = :idUsuario AND fecha_expiracion > NOW()");
            $queryToken->execute(['idUsuario' => $userExists['id']]);
    
            $tokenValido = $queryToken->fetch(PDO::FETCH_ASSOC);

            if(!$tokenValido){
                $token = bin2hex(random_bytes(32));
                
                $queryToken = $conexion->prepare("INSERT INTO token_usuario (id_usuario, token, fecha_creacion, fecha_expiracion) VALUES (:idUsuario, :token, NOW(), NOW() + INTERVAL 1 HOUR)");
                $queryToken->execute([
                    'idUsuario' => $userExists['id'],
                    'token' => $token
                ]);
                session_start();
                $_SESSION['token'] = $token;
            } else {
                session_start();
                $_SESSION['token'] = $tokenValido['token'];
            }
            $_SESSION['iniciada'] = true;
            $_SESSION['id'] = $userExists['id'];
            $_SESSION['usuario'] = $userExists['nombre'];
            $_SESSION['avatar'] = $userExists['avatar'];
            $_SESSION['carrito'] = Carrito::cargarCarrito();
        }else{
            $query = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, correo, avatar, external_id, external_auth) VALUES (:nombre, :apellidos, :correo, :avatar, :external_id, :external_auth)");
            $query->execute([
                'nombre' => $user->user['given_name'],
                'apellidos' => $user->user['family_name'],
                'correo' => $user->email,
                'avatar' => $user->avatar,
                'external_id' => $user->id,
                'external_auth' => 'google'
            ]);

            $nuevoUsuarioId = $conexion->lastInsertId();

            $queryToken = $conexion->prepare("SELECT * FROM token_usuario WHERE id_usuario = :idUsuario AND fecha_expiracion > NOW()");
            $queryToken->execute(['idUsuario' => $nuevoUsuarioId]);

            $token = $queryToken->fetch(PDO::FETCH_ASSOC)['token'];

            session_start();
            $_SESSION['token'] = $token;
            $_SESSION['iniciada'] = true;
            $_SESSION['id'] = $nuevoUsuarioId;
            $_SESSION['usuario'] = $user->user['given_name'];
            $_SESSION['avatar'] = $user->avatar;
            $_SESSION['carrito'] = [];
        }
    
        return redirect('/');    
    }

    public static function login(Request $request){
        $bd = new BD();
        $conexion = $bd->conectar();
    
        $correo = $request->input('correo');
        $contraseña = $request->input('contraseña');
    
        $queryUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $queryUsuario->execute([
            'correo' => $correo,
        ]);
    
        $usuario = $queryUsuario->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            $idUsuario = $usuario['id'];

            $query = $conexion->prepare("SELECT * FROM token_usuario WHERE id_usuario = :idUsuario AND fecha_expiracion > NOW()");
            $query->execute(['idUsuario' => $idUsuario]);

            $tokenValido = $query->fetch(PDO::FETCH_ASSOC);
            
            if(!$tokenValido){
                $token = bin2hex(random_bytes(32));
                
                $queryToken = $conexion->prepare("INSERT INTO token_usuario (id_usuario, token, fecha_creacion, fecha_expiracion) VALUES (:idUsuario, :token, NOW(), NOW() + INTERVAL 1 HOUR)");
                $queryToken->execute([
                    'idUsuario' => $idUsuario,
                    'token' => $token
                ]);
                session_start();
                $_SESSION['token'] = $token;
            } else {
                session_start();
                $_SESSION['token'] = $tokenValido['token'];
            }
    
            $_SESSION['iniciada'] = true;
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['id'] = $idUsuario;
            $_SESSION['avatar'] = $usuario['avatar'];
            
            $carrito = Carrito::cargarCarrito();
            $_SESSION['carrito'] = $carrito;
    
            return true;
        } else {
            return false;
        }
    }

    public static function logout(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        };
        if(isset($_SESSION['iniciada'])){
            $carrito = $_SESSION['carrito'];
            $id_usuario = $_SESSION['id'];
        }
        $bd = new BD();
        $conexion = $bd->conectar();

        if(!empty($carrito)){
            $query = $conexion->prepare("INSERT IGNORE INTO carritos (id_usuario) VALUES (:id)");
            $query ->execute([
                'id' => $id_usuario
            ]);

            $query = $conexion->prepare("SELECT id FROM carritos WHERE id_usuario = :id"); 
            $query->execute([
                'id' => $id_usuario
            ]);

            $carritoUsuario = $query->fetch(PDO::FETCH_ASSOC);

            $idCarrito = $carritoUsuario['id'];

            $query = $conexion->prepare("DELETE FROM zapatillas_carrito WHERE id_carrito = :id_carrito");
            $query->execute([
                'id_carrito' => $idCarrito
            ]);

            foreach ($carrito as $producto) {
                $id = $producto['id_zapatilla'];
                $id_talla = $producto['id_talla'];
                $cantidad = $producto['cantidad'];
                $query = $conexion->prepare("INSERT INTO zapatillas_carrito (id_carrito, id_zapatilla, id_talla, cantidad) VALUES (:id_carrito, :id_zapatilla, :id_talla, :cantidad)");
                $query->execute([
                    'id_carrito' => $idCarrito,
                    'id_zapatilla' => $id,
                    'id_talla' => $id_talla,
                    'cantidad' => $cantidad
                ]);
            }
        }

        $bd->cerrarConexion();

        session_destroy();

        $ruta = request()->headers->get('referer');
        if (strpos($ruta, 'sneaker') !== false) {
            return redirect('/login');
        }        

        return redirect('/');
    }
    public static function storeUsuario(Request $request){
        try{
            $bd = new BD();
            $conexion = $bd->conectar();

            $query = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contraseña, género)
                                         VALUES (:nombre, :correo, :password, :genero)");

            $resultado = $query->execute([
                'nombre' => $request->input('nombre'),
                'correo' => $request->input('correo'),
                'password' => password_hash($request->input('contraseña'), PASSWORD_BCRYPT),
                'genero' => $request->input('genero'),
            ]);

            $bd->cerrarConexion();

            return $resultado;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function comprobarToken(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['iniciada'])){
            return false;
        }
        $id_usuario = $_SESSION['id'];
        $token = $_SESSION['token'];

        $bd = new BD();
        $conexion = $bd->conectar();

        $queryToken = $conexion->prepare("SELECT * FROM token_usuario WHERE id_usuario = :idUsuario AND token = :token AND fecha_expiracion > NOW()");

        $queryToken->execute([
            'idUsuario' => $id_usuario,
            'token' => $token
        ]);

        $tokenValido = $queryToken->fetch(PDO::FETCH_ASSOC);

        if($tokenValido){
            return true;
        }else{
            return false;
        }
    }
}