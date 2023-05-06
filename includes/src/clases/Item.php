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
                $inventario[] = new Item($row['id'], $row['nombre'], $row['rareza'],$row['altura'], $row['anchura'],$row['tamanyo'],$row['pos_x'], $row['pos_y'], $row['precioMin']);
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
        $nombreItem = $item->getNombreItem();
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
        $idInv = sprintf("SELECT id_inv FROM inventario_usuario WHERE id_usuario = '%d' AND nombre_item = '%s' AND rareza = '%s'", 
                 $conn->real_escape_string($idUsuario),
                 $conn->real_escape_string($nombreItem),
                 $conn->real_escape_string($rareza)
        );
        $query = sprintf(
            "DELETE FROM inventario_usuario WHERE id_usuario = '%d' AND id_inv = '%d'",
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
                $result = new Item($fila['id'], $fila['nombre'], $fila['rareza'], $fila['altura'], $fila['anchura'], $fila['tamanyo'],$fila['pos_x'], $fila['pos_y'], $fila['precioMin']);
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
                $result = new Item($fila['id'], $fila['nombre'], $fila['rareza'], $fila['altura'], $fila['anchura'], $fila['tamanyo'],$fila['pos_x'], $fila['pos_y'], $fila['precioMin']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private $id;
    private $nombre;
    private $rareza;
    private $altura;
    private $anchura;
    private $tamanyo;
    private $pos_x;
    private $pos_y;
    private $precioMin;

    private function __construct($id, $nombre, $rareza, $altura, $anchura, $tamanyo, $pos_x,$pos_y, $precioMin) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->rareza = $rareza;
        $this->altura = $altura;
        $this->anchura = $anchura;
        $this->tamanyo = $tamanyo;
        $this->pos_x = $pos_x;
        $this->pos_y = $pos_y;
        $this->precioMin = $precioMin;
    }
    public function getIdInv(){
        return $this->id_inventario;
    }
    public function getId() {
        return $this->id;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getRareza() {
        return $this->rareza;
    }
    
    public function getAltura() {
        return $this->altura
;
    }
    public function getAnchura() {
        return $this->anchura;
    }
    public function gettamanyo() {
        return $this->tamanyo;
    }

    public function getX() {
        return $this->pos_x;
    }

    public function getY() {
        return $this->pos_y;
    }
    
    public function getPrecioMin() {
        return $this->precioMin;
    }
}
