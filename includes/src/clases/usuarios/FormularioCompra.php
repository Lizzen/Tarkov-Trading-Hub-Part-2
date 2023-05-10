<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;

class FormularioCompra extends Formulario
{
    private $item;
    private $id_usuario_comprador;

    public function __construct($item, $id_usuario_comprador)
    {
        parent::__construct('formCompra', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./mercado.php?id=compra'),
        ]);
        $this->item = $item;
        $this->id_usuario_comprador = $id_usuario_comprador;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS

        <button class="btn" type="submit" name="comprar">Comprar</button>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        if (isset($datos['comprar'])) {
            Item_mercado::comprarItem($this->item, $this->id_usuario_comprador);
        }
    }
}
