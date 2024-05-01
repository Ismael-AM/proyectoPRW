<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;
use App\Models\Zapatilla;
use App\Models\BD;

use PDO;

class ZapatillaController extends Controller
{   
    public static function index(Request $request) {
        $page = $request->input('page', 1);
    
        $curl = curl_init();
        
        if($request->has('orderby')) {
            $orderby = $request->input('orderby');
            if(in_array($orderby, ['precioAsc', 'precioDesc', 'nombreA-Z', 'nombreZ-A', 'maxDesc'])) {
                $url = 'http://bambasneakers.es/api/bambas/zapatillas?orderby=' . $orderby . '&page=' . $page;
                $path = url('/sneakers?orderby=' . $orderby);
            }
        } else {
            $path = url('/sneakers');
            $url = 'http://bambasneakers.es/api/bambas/zapatillas?page=' . $page;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($curl);
    
        if ($response === false) {
            return view('home');
        }
    
        curl_close($curl);
        $data = json_decode($response, true);
        $zapatillas = $data['data'];
        $perPage = $data['per_page'];
        $total = $data['total'];
    
        $paginator = new LengthAwarePaginator(
            $zapatillas, 
            $total, 
            $perPage, 
            $page
        );
    
        
        $paginator->setPath($path);
    
        return view('zapatillas.index', ['zapatillas' => $paginator]);
    }

    public static function indexMarcas(Request $request, $marca) {
        $page = $request->input('page', 1);
    
        $curl = curl_init();
    
        $url = "http://bambasneakers.es/api/bambas/zapatillas/marca/{$marca}?page={$page}";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($curl);
    
        if ($response === false) {
            return view('home');
        }
    
        curl_close($curl);
        $data = json_decode($response, true);
        $zapatillas = $data['data'];
        $perPage = $data['per_page'];
        $total = $data['total'];
    
        $paginator = new LengthAwarePaginator(
            $zapatillas, 
            $total, 
            $perPage, 
            $page
        );
    
        $path = url("/zapatillas/{$marca}");
        $paginator->setPath($path);
    
        return view('zapatillas.index', ['zapatillas' => $paginator]);
    }
    

    public static function search(Request $request) {
        $page = $request->input('page', 1);        
        $busqueda = urlencode($request->input('param'));

        if($request->has('orderby')) {
            $orderby = $request->input('orderby');
            if(in_array($orderby, ['precioAsc', 'precioDesc', 'nombreA-Z', 'nombreZ-A', 'maxDesc'])) {
                $url = 'http://bambasneakers.es/api/bambas/zapatillas/search?param=' . $busqueda . '&orderby=' . $orderby . '&page=' . $page;
                $path = url('/sneakers?param=' . $busqueda . '&orderby=' . $orderby);
            }else{
                $url = 'http://bambasneakers.es/api/bambas/zapatillas/search?param=' . $busqueda . '&page=' . $page;
                $path = url('/sneakers/search?param=' . $busqueda);
            }
        } else {
            $url = 'http://bambasneakers.es/api/bambas/zapatillas/search?param=' . $busqueda . '&page=' . $page;
            $path = url('/sneakers/search?param=' . $busqueda);
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
            return view('zapatillas.index', ['zapatillas' => []]);
        }

        curl_close($curl);

        $data = json_decode($response, true);
        $zapatillas = $data['data'];
        $perPage = $data['per_page'];
        $total = $data['total'];

        if(count($zapatillas) == 1) {
            return redirect('/sneaker/' . $zapatillas[0]['id']);
        }

        $paginator = new LengthAwarePaginator(
            $zapatillas,
            $total,
            $perPage,
            $page
        );

        $paginator->setPath($path);

        return view('zapatillas.index', ['zapatillas' => $paginator]);
    }

    public static function getZapatillas(Request $request) {
        $bd = new BD();
        $conexion = $bd->conectar();

        $maximoPagina = 20;

        if($request->has('orderby')) {
            $orderby = $request->input('orderby');
            if(in_array($orderby, ['precioAsc', 'precioDesc', 'nombreA-Z', 'nombreZ-A', 'maxDesc'])) {
                if($orderby == 'precioAsc') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z 
                        JOIN marcas m ON z.id_marca = m.id 
                        JOIN categorias c ON z.id_categoria = c.id
                        ORDER BY z.precio ASC
                    ");
                } elseif($orderby == 'precioDesc') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z 
                        JOIN marcas m ON z.id_marca = m.id 
                        JOIN categorias c ON z.id_categoria = c.id
                        ORDER BY z.precio DESC
                    ");
                } elseif($orderby == 'nombreA-Z') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z 
                        JOIN marcas m ON z.id_marca = m.id 
                        JOIN categorias c ON z.id_categoria = c.id
                        ORDER BY z.nombre ASC
                    ");
                } elseif($orderby == 'nombreZ-A') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z 
                        JOIN marcas m ON z.id_marca = m.id 
                        JOIN categorias c ON z.id_categoria = c.id
                        ORDER BY z.nombre DESC
                    ");
                } elseif($orderby == 'maxDesc') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z 
                        JOIN marcas m ON z.id_marca = m.id 
                        JOIN categorias c ON z.id_categoria = c.id
                        ORDER BY (z.pvp - z.precio) DESC
                    ");
                }
                
            }
        }else{
            $query = $conexion->prepare("
                SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z 
                JOIN marcas m ON z.id_marca = m.id 
                JOIN categorias c ON z.id_categoria = c.id
                ORDER BY fecha_lanzamiento DESC
            ");
        }
    
        $query->execute();
    
        $zapatillas = $query->fetchAll(PDO::FETCH_OBJ);
        $zapatillasPaginadas = array_slice($zapatillas, ($maximoPagina * (request()->page ?? 1)) - $maximoPagina, $maximoPagina);
    
        $totalZapatillas = count($zapatillas);
    
        $zapatillasPaginadas = new \Illuminate\Pagination\LengthAwarePaginator(
            $zapatillasPaginadas,
            $totalZapatillas,
            $maximoPagina,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $bd->cerrarConexion();
    
        return response()->json($zapatillasPaginadas);
    }

    public static function getZapatillaById($id){
        $bd = new BD();
        $conexion = $bd->conectar();
    
        $query = $conexion->prepare("
            SELECT z.*, m.nombre AS marca, c.nombre AS categoria, tz.stock, t.número AS talla
            FROM zapatillas z 
            JOIN marcas m ON z.id_marca = m.id 
            JOIN categorias c ON z.id_categoria = c.id 
            JOIN tallas_zapatilla tz ON z.id = tz.id_zapatilla
            JOIN tallas t ON tz.id_talla = t.id
            WHERE z.id = :id
        ");
    
        $query->execute(['id' => $id]);
        $zapatilla = null;
        $tallas = [];
    
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            if (!$zapatilla) {
                $zapatilla = [
                    'id' => $row->id,
                    'nombre' => $row->nombre,
                    'descripción' => $row->descripción,
                    'pvp' => $row->pvp,
                    'precio' => $row->precio,
                    'imagen' => $row->imagen,
                    'id_marca' => $row->id_marca,
                    'id_categoria' => $row->id_categoria,
                    'marca' => $row->marca,
                    'categoria' => $row->categoria,
                ];
            }
        
            $tallas[] = [
                'talla' => $row->talla,
                'stock' => $row->stock
            ];
        }
        
        if ($zapatilla) {
            $zapatilla['tallas'] = $tallas;
            return response()->json($zapatilla);
        } else {
            return response()->json(['error' => 'Zapatilla no encontrada'], 404);
        }
    
        $bd->cerrarConexion();
    }    

    public static function getZapatillasBySearch(Request $request){
        $bd = new BD();
        $conexion = $bd->conectar();
        
        $search = urldecode($request->input('param'));
        if($request->has('orderby')) {
            $orderby = $request->input('orderby');
            if(in_array($orderby, ['precioAsc', 'precioDesc', 'nombreA-Z', 'nombreZ-A', 'maxDesc'])) {
                if($orderby == 'precioAsc') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
                        JOIN marcas m ON z.id_marca = m.id
                        JOIN categorias c ON z.id_categoria = c.id
                        WHERE z.nombre LIKE CONCAT('%', :search, '%') OR m.nombre LIKE CONCAT('%', :search, '%') OR c.nombre LIKE CONCAT('%', :search, '%')
                        ORDER BY z.precio ASC
                    ");
                } elseif($orderby == 'precioDesc') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
                        JOIN marcas m ON z.id_marca = m.id
                        JOIN categorias c ON z.id_categoria = c.id
                        WHERE z.nombre LIKE CONCAT('%', :search, '%') OR m.nombre LIKE CONCAT('%', :search, '%') OR c.nombre LIKE CONCAT('%', :search, '%')
                        ORDER BY z.precio DESC
                    ");
                } elseif($orderby == 'nombreA-Z') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
                        JOIN marcas m ON z.id_marca = m.id
                        JOIN categorias c ON z.id_categoria = c.id
                        WHERE z.nombre LIKE CONCAT('%', :search, '%') OR m.nombre LIKE CONCAT('%', :search, '%') OR c.nombre LIKE CONCAT('%', :search, '%')
                        ORDER BY z.nombre
                    ");
                } elseif($orderby == 'nombreZ-A') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
                        JOIN marcas m ON z.id_marca = m.id
                        JOIN categorias c ON z.id_categoria = c.id
                        WHERE z.nombre LIKE CONCAT('%', :search, '%') OR m.nombre LIKE CONCAT('%', :search, '%') OR c.nombre LIKE CONCAT('%', :search, '%')
                        ORDER BY z.nombre DESC
                    ");
                } elseif($orderby == 'maxDesc') {
                    $query = $conexion->prepare("
                        SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
                        JOIN marcas m ON z.id_marca = m.id
                        JOIN categorias c ON z.id_categoria = c.id
                        WHERE z.nombre LIKE CONCAT('%', :search, '%') OR m.nombre LIKE CONCAT('%', :search, '%') OR c.nombre LIKE CONCAT('%', :search, '%')
                        ORDER BY (z.pvp - z.precio) DESC
                    ");
                }
                
            }
        }else{
            $query = $conexion->prepare("
                SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
                JOIN marcas m ON z.id_marca = m.id
                JOIN categorias c ON z.id_categoria = c.id
                WHERE z.nombre LIKE CONCAT('%', :search, '%') OR m.nombre LIKE CONCAT('%', :search, '%') OR c.nombre LIKE CONCAT('%', :search, '%')
                ORDER BY fecha_lanzamiento DESC
            ");
        }

        $maximoPagina = 20;
        
        $query->execute(['search' => $search]);

        $zapatillas = $query->fetchAll(PDO::FETCH_OBJ);

        $zapatillasPaginadas = array_slice($zapatillas, ($maximoPagina * (request()->page ?? 1)) - $maximoPagina, $maximoPagina);

        $totalZapatillas = count($zapatillas);

        $zapatillasPaginadas = new \Illuminate\Pagination\LengthAwarePaginator(
            $zapatillasPaginadas,
            $totalZapatillas,
            $maximoPagina,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        
        $bd->cerrarConexion();

        return response()->json($zapatillasPaginadas);
    }

    public static function view($id){
        $response = Http::get('http://bambasneakers.es/api/bambas/zapatillas/' . $id);
        $data = $response->json();
        $zapatilla = $data;

        $recomendaciones = self::getRecomendaciones($id);
        
        return view('zapatillas.view', ['zapatilla' => $zapatilla, 'recomendaciones' => $recomendaciones]);
    }

    public static function getRecomendaciones($id){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        
        $bd = new BD();
        $conexion = $bd->conectar();
    
        if(isset($_SESSION['id'])){
            $idUsuario = $_SESSION['id'];

            $queryUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE id = :idUsuario");
            $queryUsuario->execute(['idUsuario' => $idUsuario]);
            $usuario = $queryUsuario->fetch(PDO::FETCH_OBJ);
            
            $genero = $usuario->género;

            if($genero == 'Otro'){
                $genero = 'Unisex';
            }
            
            $query = $conexion->prepare("
                SELECT z.*, m.nombre AS marca, c.nombre AS categoria
                FROM zapatillas z 
                JOIN marcas m ON z.id_marca = m.id 
                JOIN categorias c ON z.id_categoria = c.id 
                WHERE c.nombre = :genero
                AND NOT z.id = :id
                ORDER BY RAND()
                LIMIT 10
            ");
            
            $query->execute(['genero' => $genero, 'id' => $id]);
            
            $zapatillas = $query->fetchAll(PDO::FETCH_OBJ);
            
            $bd->cerrarConexion();
            return $zapatillas;
        }else{
            $conexion = $bd->conectar();

            $query = $conexion->prepare("
                SELECT z.*, m.nombre AS marca, c.nombre AS categoria
                FROM zapatillas z 
                JOIN marcas m ON z.id_marca = m.id 
                JOIN categorias c ON z.id_categoria = c.id 
                WHERE c.nombre = 'Unisex'
                AND NOT z.id = :id
                ORDER BY RAND()
                LIMIT 10
            ");
            
            $query->execute(['id' => $id]);
            
            $zapatillas = $query->fetchAll(PDO::FETCH_OBJ);
            
            $bd->cerrarConexion();
            return $zapatillas;
        }
    }

    public function añadirAlCarrito($idZapatilla, Request $request){
        $idTalla = $request->input('talla');
    
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        $tokenValido = Usuario::comprobarToken();

        $curl = curl_init();

        $url = 'http://bambasneakers.es/api/bambas/zapatillas/' . $idZapatilla;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
            return response()->json(['success' => false]);
        }

        curl_close($curl);
        $zapatilla = json_decode($response, true);

        if(!$tokenValido){
            return response()->json(['token' => false]);
        }

        $bd = new BD();
        $conexion = $bd->conectar();

        $query = $conexion->prepare("SELECT * FROM tallas_zapatilla WHERE id_zapatilla = :idZapatilla AND id_talla = :idTalla");
        $query->execute(['idZapatilla' => $idZapatilla, 'idTalla' => $idTalla]);

        $talla = $query->fetch(PDO::FETCH_OBJ);

        if(!$talla){
            return response()->json(['success' => false]);
        }
    
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
    
        $productoEnCarrito = false;
    
        foreach($carrito as $producto){
            if($producto['id_zapatilla'] == $idZapatilla && $producto['id_talla'] == $idTalla){
                $producto['cantidad']++;
                $productoEnCarrito = true;
                break;
            }
        }
    
        if(!$productoEnCarrito){
            $carrito[] = [
                'id_zapatilla' => $idZapatilla,
                'id_talla' => $idTalla,
                'precio' => intval($zapatilla['precio']),
                'cantidad' => 1
            ];
        }
    
        $_SESSION['carrito'] = $carrito;
    
        return response()->json(['success' => true]);
    }
    
    public function eliminarDelCarrito($idZapatilla, Request $request){
        $idTalla = $request->input('talla');
    
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    
        $tokenValido = UsuarioController::comprobarToken();
    
        if(!$tokenValido){
            UsuarioController::logout();
            return response()->json(['token' => false]);
        }
        
        $carrito = $_SESSION['carrito'] ?? [];
    
        if(empty($carrito)){
            return response()->json(['success' => false]);
        }
    
        $eliminado = false;
        foreach($carrito as $key => $producto){
            if($producto['id_zapatilla'] == $idZapatilla && $producto['id_talla'] == $idTalla){
                unset($carrito[$key]);
                $eliminado = true;
                break;
            }
        }
    
        if(!$eliminado){
            return response()->json(['success' => false]);
        }
    
        $_SESSION['carrito'] = $carrito;
    
        return response()->json(['success' => true]);
    }

    public static function getNovedades(){

        $bd = new BD();
        $conexion = $bd->conectar();

        $query = $conexion->prepare("
            SELECT z.*, m.nombre AS marca, c.nombre AS categoria FROM zapatillas z
            JOIN marcas m ON z.id_marca = m.id
            JOIN categorias c ON z.id_categoria = c.id
            ORDER BY fecha_lanzamiento DESC
            LIMIT 10
        ");

        $query->execute();

        $zapatillas = $query->fetchAll(PDO::FETCH_OBJ);

        $bd->cerrarConexion();

        return $zapatillas;
    }      
}