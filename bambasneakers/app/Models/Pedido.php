<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'pedidos';
    protected $fillable = ['id_usuario', 'precioFinal', 'fecha'];

    public static function storePedido($carrito, $precioFinal){
        try{
            $bd = new BD();
            $conexion = $bd->conectar();
    
            $query = $conexion->prepare("INSERT INTO pedidos (id_usuario, precioFinal, fecha) VALUES (:id, :precioFinal, :fecha)");
            $query ->execute([
                'id' => $_SESSION['id'],
                'precioFinal' => $precioFinal,
                'fecha' => date('Y-m-d H:i:s')
            ]);
    
            $id = $conexion->lastInsertId();
    
            foreach ($carrito as $producto) {
                $query = $conexion->prepare("DELETE FROM zapatillas_carrito WHERE id_zapatilla = :id_zapatilla AND id_talla = :id_talla");
                $query ->execute([
                    'id_zapatilla' => $producto['id_zapatilla'],
                    'id_talla' => $producto['id_talla']
                ]);
                
                $query = $conexion->prepare("INSERT INTO zapatillas_pedido (id_pedido, id_zapatilla, id_talla, precio, cantidad) VALUES (:id, :id_zapatilla, :id_talla, :precio, :cantidad)");
                $query ->execute([
                    'id' => $id,
                    'id_zapatilla' => $producto['id_zapatilla'],
                    'id_talla' => $producto['id_talla'],
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad']
                ]);
            }
    
            return true;
        }catch(Exception $e){
            return $response->json(['success' => false]);
        }
    }
}
