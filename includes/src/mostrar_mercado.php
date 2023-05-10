<?php

use es\ucm\fdi\aw\clases\Item_mercado;
use es\ucm\fdi\aw\clases\usuarios\FormularioCompra;

function muestra_mercado()
{
    $html = listarCompras();
    echo $html;
}

function muestra_inicio()
{
    $html = <<<EOS
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="title">Comprar</div>
                <div class="details">
                    <button class="detail-button" onclick="window.location.href='mercado.php?id=compra'">Ver detalles</button>
                </div>
            </div>
        
            <div class="box">
                <div class="title">Vender</div>
                <div class="details">
                    <button class="detail-button" onclick="window.location.href='venta.php'">Ver detalles</button>
                </div>
            </div>
        
            <!--<div class="box">
                <div class="title">Mis ventas</div>
                <div class="details">
                    <button class="detail-button" onclick="window.location.href='venta.php'">Ver detalles</button>
                </div>
            </div>-->
        </div>
    </div>  
    EOS;

    echo $html;
}

function listarCompras()
{
    $listaItems = Item_mercado::itemsVenta($_SESSION['idUsuario']);
    if (empty($listaItems)) {
        return '<p>No hay items en el mercado</p>';
    }

    $items = '';
    foreach ($listaItems as $venta) {
        $precio = $venta->getPrecio();
        $tipo = $venta->getTipo();
        $nombre_item = $venta->getNombre_intercambio();
        if ($tipo == "intercambio") {
            $precio = "<span class='precio-intercambio'> <img src='./css/img/img_items/{$nombre_item}.png' alt='{$nombre_item}'/></span>";
        } else if ($tipo == "dual") {
            $precio .= "<span class='precio-intercambio'><->$nombre_item</span>";
        }

        $formularioCompra = new FormularioCompra($venta, $_SESSION['idUsuario']);
        $botonCompra = $formularioCompra->gestiona();        

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$venta->getNombre()}.png' alt='{$venta->getNombre()}'/>
            </div>

            <div class="nombre_item">
                {$venta->getNombre()}
            </div>
            
            <div class="nombre_usuario">
                {$venta->getNombreUsuario($venta->getId_usuario())}
            </div>

            <div class="precio_item">
                {$precio} 
            </div>

            <div class="comprar_item">
                {$botonCompra}
            </div>

        </div>
        EOS;
    }

    $html = <<<EOS
    <div class="guia">
        <div>Foto</div>
        <div class = "div-opacidad">Nombre item</div>
        <div class = "div-opacidad">Nombre usuario</div>
        <div class = "div-opacidad">Precio</div>
        <div class = "div-opacidad">intercambio</div>
        <div class = "div-opacidad">Comprar</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}
