<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;

class FormularioVenta extends Formulario
{
    private $idItem;

    public function __construct($idItem)
    {
        parent::__construct('formVenta', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./venta.php'),
        ]);
        $this->idItem = $idItem;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS
        
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        //codigo js para preguntar precio
        //js para saber si intercambio que, si es asi, preguntar objeto
        echo '<script src="./js/venta.js"></script>';
        //crear item
        if (isset($datos['vender'])) {
            Item_mercado::venderItem($this->item->getNombre(), $_SESSION['idUsuario']);
        }
    }
}