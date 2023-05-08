<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

class Item_mercado
{
    public static function venderItem($item) {
        Item::eliminarItemInventario($item->getNombreItem(), $item->getId_usuario());
        self::aniadirItemMercado($item);
    }

    public static function aniadirItemMercado($item) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $insert = sprintf("INSERT INTO `ventas_mercado` (`id_usuario`, `nombre_item`, `tipo`, `precio`, `nombre_intercambio`) 
                            VALUES (%d, '%s', '%s', %d, ,'%s')", 
                            $item->getId_usuario(), $conn->real_escape_string($item->getNombreItem()), $conn->real_escape_string($item->getTipo()),
                            $item->getPrecio(), $conn->real_escape_string($item->getNombre_intercambio()));
        if (!$conn->query($insert)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function itemsVenta($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaVentas = [];
        $sql = "SELECT * FROM ventas_mercado WHERE id_usuario != $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaVentas[] = new Item_mercado($row['id_venta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
            }
        }
        return $listaVentas;
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

                self::borraPorItemYUsuario($item->getNombreItem(), $item->getId_usuario());
                break;

            case 'intercambio':
                // Pasan item a tu inventario ¿comprobar si cabe item?
                Item::aniadirAInventario($item, $id_usuario_comprador);

                self::borraPorItemYUsuario($item->getNombreItem(), $item->getId_usuario());
                break;

            case 'dual':
                // Te quitan dinero y pasan item a tu inventario ¿comprobar si cabe item?
                Usuario::restaDinero($item->getPrecio(), $id_usuario_comprador);
                Item::aniadirAInventario($item, $id_usuario_comprador);

                // Mandar dinero y item a vendedor
                Usuario::sumaDinero($item->getPrecio(), $item->getId_usuario());
                Item::aniadirAInventario($item, $item->getId_usuario());

                // Eliminar item mercado
                self::borraPorItemYUsuario($item->getNombreItem(), $item->getId_usuario());
                break;
        }
    }


    public static function borraPorItemYUsuario($nombre_item, $id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "DELETE FROM ventas_mercado WHERE id_usuario = %d AND nombre_item = '%s'",
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
        $sql = "SELECT * FROM ventas_mercado WHERE nombre_item='$nombreItem'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $item_mercado = new Item_mercado($row['id_venta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
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



    private $id_venta;
    private $nombre_item;
    private $id_usuario;
    private $tipo;
    private $precio;
    private $nombre_intercambio;
    private function __construct($id_venta, $nombre_item, $id_usuario, $tipo, $precio, $nombre_intercambio)
    {
        $this->id_venta = $id_venta;
        $this->nombre_item = $nombre_item;
        $this->id_usuario = $id_usuario;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->nombre_intercambio = $nombre_intercambio;
    }
    public function getId()
    {
        return $this->id_venta;
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

    public function getNombre_intercambio()
    {
        return $this->nombre_intercambio;
    }

    public function getNombreUsuario($id)
    {
        return Usuario::buscaNombreUsuario($id);
    }
}
