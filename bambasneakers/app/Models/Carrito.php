<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use PDO;

class Carrito extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'carritos';
    protected $fillable = ['id_usuario'];

    public static function cargarCarrito(){
        $id_usuario = $_SESSION['id'];
        $bd = new BD();
        $conexion = $bd->conectar();
    
        $query = $conexion->prepare("SELECT * FROM carritos WHERE id_usuario = :id");
        $query->execute(['id' => $id_usuario]);
        $carrito = $query->fetch(PDO::FETCH_ASSOC);
    
        if(empty($carrito)){
            $query = $conexion->prepare("INSERT INTO carritos (id_usuario) VALUES (:id)");
            $query->execute(['id' => $id_usuario]);
            
            $carritoId = $conexion->lastInsertId();
        }else{
            $carritoId = $carrito['id'];
        }
    
        $query = $conexion->prepare("SELECT id_zapatilla, id_talla, cantidad FROM zapatillas_carrito WHERE id_carrito = :id");
        $query->execute(['id' => $carritoId]);
        $productos = $query->fetchAll(PDO::FETCH_OBJ);

        $bd->cerrarConexion();

        $productosArray = array_map(function($producto) {
            $bd = new BD();
            $conexion = $bd->conectar();
            $query = $conexion->prepare("SELECT * FROM zapatillas WHERE id = :id");
            $query->execute(['id' => $producto->id_zapatilla]);
            $zapatilla = $query->fetch(PDO::FETCH_ASSOC);
            return [
                "id_zapatilla" => (string) $producto->id_zapatilla,
                "id_talla" => (string) $producto->id_talla,
                "precio" => (int) $zapatilla['precio'],
                "cantidad" => (int) $producto->cantidad
            ];
        }, $productos);
    
        $bd->cerrarConexion();

        return $productosArray;
    }

    public static function getDatosZapatillasCarrito($carrito){
        $bd = new BD();
        $conexion = $bd->conectar();
    
        $idsZapatilla = array_unique(array_column($carrito, 'id_zapatilla'));
    
        $tallas = array_unique(array_column($carrito, 'id_talla'));
    
        $placeholdersZapatilla = implode(',', array_fill(0, count($idsZapatilla), '?'));
        $placeholdersTalla = implode(',', array_fill(0, count($tallas), '?'));
    
        $query = $conexion->prepare("
            SELECT z.*, m.nombre AS marca, c.nombre AS categoria, tz.stock AS stock
            FROM ZAPATILLAS z
            JOIN marcas m ON z.id_marca = m.id
            JOIN categorias c ON z.id_categoria = c.id
            JOIN tallas_zapatilla tz ON z.id = tz.id_zapatilla
            WHERE z.id IN ({$placeholdersZapatilla}) AND tz.id_talla IN ({$placeholdersTalla})
        ");
    
        $params = [];
    
        $params = array_merge($params, array_values($idsZapatilla));
        $params = array_merge($params, array_values($tallas));
    
        $query->execute($params);
    
        $datos = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $bd->cerrarConexion();
    
        return $datos;
    }
}
