<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;
use es\ucm\fdi\aw\clases\Item;

class FormularioVenta extends Formulario
{
    private $nombreItem;

    public function __construct($nombreItem)
    {
        parent::__construct('formVenta', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./venta.php'),
        ]);
        $this->nombreItem = $nombreItem;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombres = Item::listarItems();
        $htmlNombres = '';
        foreach($nombres as $nombre) {
            $htmlNombres .= <<<EOS
            <label>
                <input type="radio" name="nombre_item" value="{$nombre}"/> {$nombre}
            </label>
            EOS;
        }

        $camposFormulario = <<<EOS
        <fieldset>
            <legend>Elige un item para el intercambio</legend>
            $htmlNombres
            <legend>Pon un precio</legend>
            <input type="number" name="precio" min="1">
            <div>
                <button class="btn" type="submit" name="seleccion">Enviar</button>
                <button class="btn" type="reset" name="borrar">Borrar</button>
            </div>
        <legend>
        </fieldset>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        $precio = $datos['precio'];
        $nombre_item_cambio = $datos['nombre_item'];

        if($nombre_item_cambio != null && $precio > 0) {
            $tipo = "dual";
        }
        else if($nombre_item_cambio == null && $precio > 0) {
            $tipo = "dinero";
        }
        else $tipo = "intercambio";
        $idventa = rand(1, 1000);
        Item_mercado::venderItem($idventa, $this->nombreItem, $_SESSION['idUsuario'], $tipo, $precio, $nombre_item_cambio);
    }
}