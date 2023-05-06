<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

class Item_subastas
{
    public static function itemsSubastas($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaSubastas = [];
        $sql = "SELECT * FROM subastas WHERE id_usuario != $id_usuario AND id_licitador != $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaSubastas[] = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador']);
            }
        }
        $result->free();
        return $listaSubastas;
    }

    public static function itemsPujas($licitador)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaPujas = [];
        $sql = "SELECT * FROM subastas WHERE id_licitador = $licitador";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaPujas[] = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador']);
            }
        }
        $result->free();
        return $listaPujas;
    }

    public static function itemsUsuario($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaSubastas = [];
        $sql = "SELECT * FROM subastas WHERE id_usuario = $id_usuario" ;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaSubastas[] = new Item_subastas($row['id_subasta'], $row['id_usuario'], $row['nombre_item'], $row['tipo'], $row['precio'], $row['id_licitador']);
            }
        }
        $result->free();
        return $listaSubastas;
    }

    public static function subastarItem($item, $idUsuario, $precio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $nombreItem = $item->getNombre();

        do {
            $idSubasta = rand(1, 1000); 
            $comprobacion = sprintf("SELECT id_subasta FROM subastas WHERE id_usuario = %d AND id_subasta = %d", $idUsuario, $idSubasta);
            $result = $conn->query($comprobacion);
        } while ($result->num_rows > 0); 
        $result->free();
        
        $insert = sprintf("INSERT INTO `subastas` (`id_subasta`, `id_usuario`, `nombre_item`, `tipo`, `precio`, `id_licitador`) VALUES (%d, %d, '%s', '%s', %f, %d)", $idSubasta, $idUsuario, $nombreItem, $item->getRareza(), $precio, NULL);
        
        Item::borrarDeInventario($nombreItem, $idUsuario, $item->getRareza());
        if (!$conn->query($insert)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function pujarItem($id_subasta, $precio, $licitador)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE subastas SET precio = %f, id_licitador = %d WHERE id_subasta = %d", $precio, $licitador, $id_subasta);
        $result = $conn->query($query);
        if (!$result) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }

        return true;

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

    private $id_subasta;
    private $nombre_item;
    private $id_usuario;
    private $tipo;
    private $precio;
    private $licitador;
    private function __construct($id_subasta, $id_usuario, $nombre_item, $tipo, $precio, $licitador)
    {
        $this->id_subasta = $id_subasta;
        $this->nombre_item = $nombre_item;
        $this->id_usuario = $id_usuario;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->licitador =$licitador;
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

    public function getNombreUsuario($id)
    {
        return Usuario::buscaNombreUsuario($id);
    }
}
