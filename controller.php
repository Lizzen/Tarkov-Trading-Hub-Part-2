<?php

require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\Item_subastas;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if(isset($_GET['identifier']) && $_GET['identifier'] === "getInventario") {
      header('Content-Type: application/json');
      echo json_encode(mostrarInventario());
    }
    else if(isset($_POST['identifier']) && $_POST['identifier'] === "actualizacionInventario"){
        if(isset($_POST['itemInventario'])){
            $item = $_POST['itemInventario'];
            $idUsuario =  $_SESSION["idUsuario"];
            Item::actualizarItemInventario($idUsuario, $item);
        }
    } 
    else if (isset($_POST['identifier']) && $_POST['identifier'] === "actualizaTiempoRestante"){
      Item_subastas::actualizarTiempoRestante();
    }
    else {
      header('HTTP/1.1 400 Bad Request');
      echo "Petición AJAX no reconocida";
    }
  } else {
    header('Location: index.php');
}

function mostrarInventario()
{
    $inventarioBD = Item::listarInventario($_SESSION["idUsuario"]); // Obtener datos de la base de datos

    // Transformar datos a formato de variable "inventario"
    $inventario = array();
    foreach ($inventarioBD as $item) {
        $nuevoItem = array();
        $nuevoItem['nombre'] = $item->getNombre();
        $nuevoItem['imagen'] = './css/img/img_items/' . $item->getNombre() . '.png';
        $nuevoItem['anchura'] = $item->getColumnas();
        $nuevoItem['altura'] = $item->getFilas();
        $nuevoItem['x'] = $item->getX();
        $nuevoItem['y'] = $item->getY();
        array_push($inventario, $nuevoItem);
    }
    return $inventario;
}