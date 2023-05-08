<?php

use es\ucm\fdi\aw\clases\usuarios\FormularioVenta;
use es\ucm\fdi\aw\clases\Item;

function muestra_inventario_ventas()
{
    $html = listarVentas();  
    echo $html;
}
function listarVentas()
{
    $listaItems = Item::listarInventario($_SESSION['idUsuario']);
    if (empty($listaItems)) {
        return '<p>No hay items en tu inventario</p>';
    }

    $items = '';
    foreach ($listaItems as $item) {

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$item->getNombre()}.png' alt='{$item->getNombre()}'/>
            </div>

            <div class="nombre_item">
                {$item->getNombre()}
            </div>
            
            <div class="rareza">
                {$item->getRareza()}
            </div>

            <div class="tamaño_inventario">
                {$item->getTamaño_inventario()} 
            </div>

            <div class="vender_item">
                <button onclick="window.location.href='ponerPrecio.php?item={$item->getIdInv()}';">Vender</button>
            </div>

        </div>
        EOS;
    }

    $html = <<<EOS
    <div class="guia">
        <div>Foto</div>
        <div class = "div-opacidad">Nombre item</div>
        <div class = "div-opacidad">Rareza</div>
        <div class = "div-opacidad">Tamaño en inventario</div>
        <div class = "div-opacidad">Venta</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}
