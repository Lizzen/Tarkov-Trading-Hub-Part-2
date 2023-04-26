<?php 

namespace es\ucm\fdi\aw\clases;
use es\ucm\fdi\aw\Aplicacion;

class Item {
    public static function listarInventario($idUsuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $inventario = [];
        $sql = sprintf("SELECT *
                        FROM inventario_usuario ui 
                        JOIN items i ON ui.nombre_item = i.nombre 
                        WHERE ui.id_usuario='%s'", $conn->real_escape_string($idUsuario));
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $inventario[] = new Item($row['nombre'], $row['rareza'],$row['tamaño_inventario'], $row['filas'],$row['columnas'],$row['pos_x'],$row['pos_y']);
            }
        }
        return $inventario;
    }





    public static function actualizarItemInventario($idUsuario, $item) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf("UPDATE inventario_usuario 
                         SET pos_x='%s', pos_y='%s'
                         WHERE id_usuario='%s' AND nombre_item='%s'",
                        $conn->real_escape_string($item['x']),
                        $conn->real_escape_string($item['y']),
                        $conn->real_escape_string($idUsuario),
                        $conn->real_escape_string($item['nombre']));
        $result = $conn->query($sql);
        if($result){
          echo "Item actualizado correctamente en la base de datos";
        }else{
          echo "Error al actualizar el item en la base de datos";
        }
    }
    

    private $nombre;
    private $rareza;
    private $tamaño_inventario;
    private $filas;
    private $columnas;
    private $pos_x;
    private $pos_y;
    private function __construct($nombre, $rareza,$tamaño_inventario, $filas, $columnas,$pos_x,$pos_y) {
        $this->nombre = $nombre;
        $this->rareza = $rareza;
        $this->tamaño_inventario = $tamaño_inventario;
        $this->filas = $filas;
        $this->columnas = $columnas;
        $this->pos_x = $pos_x;
        $this->pos_y = $pos_y;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getRareza() {
        return $this->rareza;
    }
    public function getTamaño_inventario() {
        return $this->tamaño_inventario;
    }
    public function getFilas() {
        return $this->filas;
    }
    public function getColumnas() {
        return $this->columnas;
    }

    public function getX() {
        return $this->pos_x;
    }

    public function getY() {
        return $this->pos_y;
    }
}
