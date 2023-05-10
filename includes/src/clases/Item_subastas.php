<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

class Item_subastas
{
    public static function itemsSubastas($id_usuario)
    {
        self::actualizarTiempoRestante();
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaSubastas = [];
        $sql = sprintf(
            "SELECT * FROM subastas WHERE id_usuario != %d AND id_licitador != %d",
            $id_usuario,
            $id_usuario)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaSubastas[] = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador'], $row['fecha_limite'], $row['tiempo_restante']);
            }
        }
        $result->free();
        return $listaSubastas;
    }

    public static function itemsPujas($licitador)
    {
        self::actualizarTiempoRestante();
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaPujas = [];
        $sql = sprintf(
            "SELECT * FROM subastas WHERE id_licitador = %d",
            $licitador
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaPujas[] = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador'], $row['fecha_limite'], $row['tiempo_restante']);
            }
        }
        $result->free();
        return $listaPujas;
    }

    public static function itemsUsuario($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaSubastas = [];
        $sql = sprintf(
            "SELECT * FROM subastas WHERE id_usuario = %d", 
            $id_usuario
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaSubastas[] = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador'], $row['fecha_limite'], $row['tiempo_restante']);
            }
        }
        $result->free();
        return $listaSubastas;
    }

    public static function actualizarTiempoRestante() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT id_subasta, fecha_limite FROM subastas");
        $result = $conn->query($query);
        if (!$result) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    
        while ($row = $result->fetch_assoc()) {
            $id_subasta = $row['id_subasta'];
            $fecha_limite = $row['fecha_limite'];
            $fecha_actual = date("Y-m-d H:i:s");
            $tiempo_limite = date_create($fecha_limite);
            $tiempo_actual = date_create($fecha_actual);
            $intervalo = $tiempo_actual->diff($tiempo_limite);
            
            if ($tiempo_actual >= $tiempo_limite){
                self::terminarSubasta($id_subasta);
            }
            else{
                $dias = $intervalo->d;
                $horas = $intervalo->h;
                $minutos = $intervalo->i;
                $tiempo_restante = sprintf("%d:%d:%d", $dias, $horas, $minutos);
                $update = sprintf(
                    "UPDATE subastas SET tiempo_restante = '%s' WHERE id_subasta = %d",
                    $conn->real_escape_string($tiempo_restante),
                    $id_subasta
                );
                $conn->query($update);
            }
        }
        $result->free();
    
        return true;
    }

    public static function subastarItem($item, $id_usuario, $precio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $nombreItem = $item->getNombre();

        $fechaLimite = time() + (72 * 3600);
        $fechaLimiteSql = date('Y-m-d H:i:s', $fechaLimite);

        do {
            $id_subasta = rand(1, 1000); 
            $comprobacion = sprintf(
                "SELECT id_subasta FROM subastas WHERE id_usuario = %d AND id_subasta = %d", 
                $id_usuario, 
                $id_subasta
            );
            $result = $conn->query($comprobacion);
        } while ($result->num_rows > 0); 
        $result->free();
        
        $insert = sprintf(
            "INSERT INTO `subastas` (`id_subasta`, `id_usuario`, `nombre_item`, `tipo`, `precio`, `id_licitador`, `fecha_limite`, `tiempo_restante`) VALUES (%d, %d, '%s', '%s', %f, %d, '%s', '%s')", 
            $conn->real_escape_string($id_subasta), 
            $conn->real_escape_string($id_usuario), 
            $conn->real_escape_string($nombreItem), 
            $conn->real_escape_string($item->getRareza()),
            $conn->real_escape_string($precio), 
            $conn->real_escape_string(NULL), 
            $conn->real_escape_string($fechaLimiteSql), 
            $conn->real_escape_string(NULL)
        );
        Item_subastas::actualizarTiempoRestante();
        Item::borrarDeInventario($nombreItem, $id_usuario, $item->getRareza());
        if (!$conn->query($insert)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function pujarItem($id_subasta, $precio, $licitador)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "UPDATE subastas SET precio = %f, id_licitador = %d WHERE id_subasta = %d", 
            $conn->real_escape_string($precio), 
            $conn->real_escape_string($licitador), 
            $conn->real_escape_string($id_subasta)
        );
        $result = $conn->query($query);
        if (!$result) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }

        return true;

    }
    
    public static function terminarSubasta($id_subasta)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $itemSubasta = sprintf(
            "SELECT * FROM subastas WHERE id_subasta = %d",
            $conn->real_escape_string($id_subasta)
        );
        $result = $conn->query($itemSubasta);
        $row = $result->fetch_assoc();
        $item = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador'], $row['fecha_limite'], $row['tiempo_restante']);
    
        if ($item->getLicitador() != 0){
            Item::aniadirAInventario($item, $item->getLicitador());
            Usuario::sumaDinero($item->getPrecio(), $item->getId_usuario());
            Usuario::restaDinero($item->getPrecio(), $item->getLicitador());
        }
        else{
            Item::aniadirAInventario($item, $item->getId_usuario());
        }
    
        $query = sprintf(
            "DELETE FROM subastas WHERE id_subasta = '%d'",
            $conn->real_escape_string($id_subasta)
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            throw new Exception("Error al eliminar subasta");
        }
        return true;
    }
    
    private $id_subasta;
    private $nombre_item;
    private $id_usuario;
    private $tipo;
    private $precio;
    private $licitador;
    private $fechaLimite;
    private $tiempoRestante;
    private function __construct($id_subasta, $id_usuario, $nombre_item, $tipo, $precio, $licitador, $fechaLimite, $tiempoRestante)
    {
        $this->id_subasta = $id_subasta;
        $this->nombre_item = $nombre_item;
        $this->id_usuario = $id_usuario;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->licitador =$licitador;
        $this->fechaLimite = $fechaLimite;
        $this->tiempoRestante = $tiempoRestante;
    }
    public function getId()
    {
        return $this->id_subasta;
    }
    public function getNombre()
    {
        return $this->nombre_item;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function getRareza()
    {
        return $this->tipo;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getLicitador()
    {
        return $this->licitador;
    }

    public function getFechaLimite()
    {
        return $this->fechaLimite;
    }

    public function getTiempoRestante()
    {
        return $this->tiempoRestante;
    }

    public function getNombreUsuario($id)
    {
        return Usuario::buscaNombreUsuario($id);
    }
}
