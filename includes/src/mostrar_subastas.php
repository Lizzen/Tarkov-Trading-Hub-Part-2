<?php
use es\ucm\fdi\aw\clases\usuarios\FormularioSubastas;
use es\ucm\fdi\aw\clases\usuarios\FormularioPujas;
use es\ucm\fdi\aw\clases\Item_subastas;
use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

function muestra_inicio()
{

}

function muestra_inventario()
{
    $html = listarItems($_SESSION['idUsuario']);
    echo $html;
}

function muestra_mis_subastas()
{
    $html = misSubastas($_SESSION['idUsuario']);
    echo $html;
}

function muestra_pujas()
{
    $html = listarSubastas($_SESSION['idUsuario']);
    echo $html;
}

function muestra_mis_pujas()
{
    $html = misPujas($_SESSION['idUsuario']);
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
                <img src='./css/img/img_items/{$item->getNombre()}.png' alt='{$item->getNombre()}'/>
            </div>

            <div class="nombre_item">
                {$item->getNombre()}
            </div>
            
            <div class="rareza">
                {$item->getRareza()}
            </div>

            <div class="subastar_item">
                {$botonSubastar}
            </div>

        </div>
        EOS;
    }

    $html = <<<EOS
    <div class="guia">
        <div class = "div-opacidad">Imagen</div>
        <div class = "div-opacidad">Item</div>
        <div class = "div-opacidad">Rareza</div>
        <div class = "div-opacidad">Subastar</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}

function misSubastas($idUsuario)
{
    $listaItems = Item_subastas::itemsUsuario($idUsuario);
    if (empty($listaItems)) {
        return '<p>No tienes items en subasta</p>';
    }

    $items = '';
    foreach ($listaItems as $subasta) {

        $formularioSubastas = new FormularioSubastas($subasta, $idUsuario);        

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$subasta->getNombre()}.png' alt='{$subasta->getNombre()}'/>
            </div>

            <div class="nombre_item">
                {$subasta->getNombre()}
            </div>
            
            <div class="rareza">
                {$subasta->getRareza()}
            </div>

            <div class="precio_item">
                {$subasta->getPrecio()} 
            </div>

        </div>
        EOS;
    }

    $html = <<<EOS
    <div class="guia">
        <div class = "div-opacidad">Imagen</div>
        <div class = "div-opacidad">Item</div>
        <div class = "div-opacidad">Rareza</div>
        <div class = "div-opacidad">Precio</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}

function listarSubastas($idUsuario)
{
    $listaItems = Item_subastas::itemsSubastas($idUsuario);
    if (empty($listaItems)) {
        return '<p>No hay items en subasta</p>';
    }

    $items = '';
    foreach ($listaItems as $subasta) {
        $formularioPujas = new FormularioPujas($subasta, $idUsuario);
        $botonPuja = $formularioPujas->gestiona();        

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$subasta->getNombre()}.png' alt='{$subasta->getNombre()}'/>
            </div>

            <div class="nombre_item">
                {$subasta->getNombre()}
            </div>
            
            <div class="nombre_usuario">
                {$subasta->getNombreUsuario($subasta->getId_usuario())}
            </div>

            <div class="precio_item">
                {$subasta->getPrecio()} 
            </div>

            <div class="pujar_item">
                {$botonPuja}
            </div>

        </div>
        EOS;
    }

    $html = <<<EOS
    <div class="guia">
        <div class = "div-opacidad">Imagen</div>
        <div class = "div-opacidad">item</div>
        <div class = "div-opacidad">usuario</div>
        <div class = "div-opacidad">Precio</div>
        <div class = "div-opacidad">Pujar</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}

function misPujas($idUsuario)
{
    $listaItems = Item_subastas::itemsPujas($idUsuario);
    if (empty($listaItems)) {
        return '<p>No tienes ninguna puja activa</p>';
    }

    $items = '';
    foreach ($listaItems as $subasta) {   

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$subasta->getNombre()}.png' alt='{$subasta->getNombre()}'/>
            </div>

            <div class="nombre_item">
                {$subasta->getNombre()}
            </div>
            
            <div class="nombre_usuario">
                {$subasta->getNombreUsuario($subasta->getId_usuario())}
            </div>

            <div class="precio_item">
                {$subasta->getPrecio()} 
            </div>

        </div>
        EOS;
    }
    
        $html = <<<EOS
        <div class="guia">
            <div class = "div-opacidad">Imagen</div>
            <div class = "div-opacidad">item</div>
            <div class = "div-opacidad">usuario</div>
            <div class = "div-opacidad">Precio</div>
        </div>
        <div class="lista_items">
            {$items}
        </div>
        EOS;
    
        return $html;
}
?>