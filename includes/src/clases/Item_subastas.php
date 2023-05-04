<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

class Item_subastas
{

    public static function itemsSubastas($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaSubastas = [];
        $sql = "SELECT * FROM suabstas WHERE id_usuario != $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaVentas[] = new Item_subastas($row['id_subasta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio']);
            }
        }
        return $listaSubastas;
    }

    public static function subastarItem($item){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $nombreItem = $item->getNombreItem();

        do {
            $idSubasta = rand(1, 1000); 
            $comprobacion = sprintf("SELECT id_subasta FROM subastas WHERE id_usuario = %d AND id_subasta = %d", $idUsuario, $idubasta);
            $result = $conn->query($comprobacion);
        } while ($result->num_rows > 0); 

        $insert = sprintf("INSERT INTO `subastas` (`id_subasta`, `id_usuario`, `nombre_item`, `tipo`, `precio`) VALUES (%d, %d, '%s', '%s', %d)", $idSubasta, $idUsuario, $nombreItem, $item->getTipo(), $item->getPrecio());
        $conn->query($insert);
        
        Item_subastas::borraPorItemYUsuario($item->getNombreItem(), $item->getId_usuario());
    }

    public static function comprarItem($item, $id_usuario_comprador)
    {
        $tipo_transaccion = $item->getTipo();

        switch ($tipo_transaccion) {
            case 'dinero':
                // Te quitan dinero, comprobar que tienes dinero
                Usuario::restaDinero($item->getPrecio(), $id_usuario_comprador);

                // Pasan item a tu inventario ¿comprobar si cabe item?
                Item::aniadirAInventario($item, $id_usuario_comprador);

                // Mandar dinero a vendedor
                Usuario::sumaDinero($item->getPrecio(), $item->getId_usuario());
                break;
        }
    }


    public static function borraPorItemYUsuario($nombre_item, $id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "DELETE FROM subastas WHERE id_usuario = %d AND nombre_item = '%s'",
            $id_usuario,
            $nombre_item
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }


    public static function getItemPorNombre($nombreItem)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM subastas WHERE nombre_item='$nombreItem'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $Item_subastas = new Item_subastas($row['id_subasta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
            return $item_mercado;
        }
        return null;
    }


    public static function comprobarPosicionDisponible($x, $y, $anchura, $altura, $idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $xIni = $x;
        $xFin = $x + $anchura - 1;
        $yIni = $y;
        $yFin = $y + $altura - 1;

        $query = sprintf(
            "SELECT x, y, anchura, altura FROM inventario_usuario WHERE id_usuario = %d",
            $idUsuario
        );
        $result = $conn->query($query);
        if (!$result) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }

        while ($row = $result->fetch_assoc()) {
            $itemXIni = $row["x"];
            $itemXFin = $itemXIni + $row["anchura"] - 1;
            $itemYIni = $row["y"];
            $itemYFin = $itemYIni + $row["altura"] - 1;

            if (($itemXIni >= $xIni && $itemXIni <= $xFin) || ($itemXFin >= $xIni && $itemXFin <= $xFin)) {
                if (($itemYIni >= $yIni && $itemYIni <= $yFin) || ($itemYFin >= $yIni && $itemYFin <= $yFin)) {
                    return false;
                }
            }
        }

        return true;
    }



    private $id_subasta;
    private $nombre_item;
    private $id_usuario;
    private $tipo;
    private $precio;
    private function __construct($id_subasta, $nombre_item, $id_usuario, $tipo, $precio,)
    {
        $this->id_subasta = $id_subasta;
        $this->nombre_item = $nombre_item;
        $this->id_usuario = $id_usuario;
        $this->tipo = $tipo;
        $this->precio = $precio;
    }
    public function getId()
    {
        return $this->id_subasta;
    }
    public function getNombreItem()
    {
        return $this->nombre_item;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getNombreUsuario($id)
    {
        return Usuario::buscaNombreUsuario($id);
    }

    public function setPrecioSubasta($precioSubasta)
    {
         $this->precio = $precioSubasta;
    }
}
