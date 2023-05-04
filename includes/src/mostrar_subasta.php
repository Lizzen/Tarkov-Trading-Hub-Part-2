<?php
use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

function muestra_subastas()
{
    $html = listarSubastas();
    echo $html;
}

function listarItems($idUsuario)
{
    $listaItems = Item::listarInventario($idUsuario);
    if (empty($listaItems)) {
        return '<p>No hay items en el inventario</p>';
    }

    $items = '';
    foreach ($listaItems as $item) {

        $formularioSubastas = new FormularioSubastas($item, $idUsuario);
        $botonSubastar = $formularioSubastas->gestiona(); 

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$item
                    ->getNombreItem()}.png' alt='{$item->getNombreItem()}'/>
            </div>

            <div class="nombre_item">
                {$item->getNombreItem()}
            </div>
            
            <div class="rareza">
                {$item->getRareza()}
            </div>

            <div class="precio_item">
                {$precio} 
            </div>

            <div class="subastar_item">
                {$botonSubastar}
            </div>

        </div>
        EOS;
    }
}

function listarSubastas()
{
    $listaItems = Item_subastas::itemsSubastas($_SESSION['idUsuario']);
    if (empty($listaItems)) {
        return '<p>No hay items en subasta</p>';
    }

    $items = '';
    foreach ($listaItems as $subasta) {
        $precio = $subasta->getPrecio();
        $tipo = $subasta->getTipo();

        $formularioSubastas = new FormularioSubastas($subasta, $_SESSION['idUsuario']);
        $botonPuja = $formularioCompra->gestiona();        

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$subasta->getNombreItem()}.png' alt='{$subasta->getNombreItem()}'/>
            </div>

            <div class="nombre_item">
                {$subasta->getNombreItem()}
            </div>
            
            <div class="nombre_usuario">
                {$subasta->getNombreUsuario($subasta->getId_usuario())}
            </div>

            <div class="precio_item">
                {$precio} 
            </div>

            <div class="pujar_item">
                {$botonPuja}
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
        <div class = "div-opacidad">Pujar</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}
