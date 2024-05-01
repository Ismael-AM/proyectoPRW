<?php

namespace App\Models;

use Exception;
use PDO;

class BD {
    public function conectar(){
        try {
            $dsn = 'mysql:host=localhost;user=root;dbname=proyectoIAM;charset=utf8mb4';
            $this->conexion = new PDO($dsn, 'root', '');
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion;
        } catch (PDOException $e) {
            throw new PDOException("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

    public function cerrarConexion() {
        $this->conexion = null;
    }
}
?>