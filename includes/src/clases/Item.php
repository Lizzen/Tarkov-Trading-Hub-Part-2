<?php 
namespace es\ucm\fdi\aw\clases;
use es\ucm\fdi\aw\Aplicacion;
class Item {

    public static function eliminarItemInventario($nombreItem, $idUsuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf("DELETE FROM inventario_usuario WHERE nombre_item = '%s' AND id_usuario = '%d'", $conn->real_escape_string($nombreItem), $idUsuario);
        if (!$conn->query($sql)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }
    public static function listarInventario($idUsuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $inventario = [];
        $sql = sprintf("SELECT *
                        FROM inventario_usuario ui 
                        JOIN items i ON ui.nombre_item = i.nombre 
                        WHERE ui.id_usuario='%s'", 
                        $conn->real_escape_string($idUsuario)
        );
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
    public static function aniadirAInventario($item, $idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $nombreItem = $item->getNombre();
        do {
            $idInv = rand(1, 1000); // Generamos un id aleatorio para el item
            $comprobacion = sprintf("SELECT id_inv FROM inventario_usuario WHERE id_usuario = %d AND id_inv = %d",
                                $conn->real_escape_string($idUsuario), 
                                $conn->real_escape_string($idInv)
                            );
            $result = $conn->query($comprobacion);
        } while ($result->num_rows > 0); // Si el id ya existe en la tabla, lo volvemos a intentar

        $insert = sprintf("INSERT INTO `inventario_usuario` (`id_usuario`, `id_inv`, `nombre_item`) VALUES (%d, %d, '%s')",
                    $conn->real_escape_string($idUsuario), 
                    $conn->real_escape_string($idInv), 
                    $conn->real_escape_string($nombreItem)
                );
        if (!$conn->query($insert)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function borrarDeInventario($nombreItem, $idUsuario, $rareza){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $idInvQuery = sprintf(
            "SELECT iu.id_inv FROM inventario_usuario iu 
            INNER JOIN items it 
            ON it.nombre = iu.nombre_item 
            WHERE iu.id_usuario = %d AND it.nombre = '%s' AND it.rareza = '%s'", 
            $conn->real_escape_string($idUsuario),
            $conn->real_escape_string($nombreItem),
            $conn->real_escape_string($rareza)
        );
        $result = $conn->query($idInvQuery);
        if (!$result) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }

        $idInvRow = $result->fetch_assoc();
        $idInv = $idInvRow['id_inv'];
        $result->free();
        $query = sprintf(
            "DELETE FROM inventario_usuario WHERE id_usuario = %d AND id_inv = %d",
            $conn->real_escape_string($idUsuario),
            $conn->real_escape_string($idInv)
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function buscaIdItem($item, $rareza)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Item($fila['nombre'], $fila['rareza'], $fila['tamaño_inventario'], $fila['filas'], $fila['columnas'], $fila['pos_x'], $fila['pos_y']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
    public static function buscaItem($nombreItem)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM items U WHERE U.nombre='%s'", $conn->real_escape_string($nombreItem));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Item($fila['nombre'], $fila['rareza'], $fila['tamaño_inventario'], $fila['filas'], $fila['columnas'], $fila['pos_x'], $fila['pos_y']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private $id_inventario;
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
    public function getIdInv(){
        return $this->id_inventario;
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
