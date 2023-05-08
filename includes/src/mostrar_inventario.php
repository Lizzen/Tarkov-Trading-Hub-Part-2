<?php

use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

function mostrarInventario($idUsuario) {
    $inventario = Item::listarInventario($idUsuario);
    $user = Usuario::buscaPorId($idUsuario);

    if (empty($inventario)) {
        echo "El inventario está vacío.";
        return;
    }

    echo "<ul id='inventario' class='inventario'></ul>";
    
}


?>
