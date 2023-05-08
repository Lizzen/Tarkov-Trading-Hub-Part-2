<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;

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
        $camposFormulario = <<<EOS
        <fieldset>
        <legend>Elige el tipo de transacción</legend>
            <label>
                <input type="radio" name="tipo" value="dinero"/> Dinero
            </label>
            <label>
                <input type="radio" name="tipo" value="intercambio"/> Intercambio
            </label>
            <label>
                <input type="radio" name="tipo" value="dual"/> Dual
            </label>
            <div>
                <button type="submit" name="seleccion">Enviar</button>
            </div>
        </fieldset>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        if($datos['tipo'] == "dinero") {
            
        }
        else if($datos['tipo'] == "intercambio") {

        }
        else {

        }
        //crear item
        $idventa = rand(1, 1000);
        $item = new Item_mercado($idventa, );
        if (isset($datos['vender'])) {
            Item_mercado::venderItem($item, $_SESSION['idUsuario']);
        }
    }
}