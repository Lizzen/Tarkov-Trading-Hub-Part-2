<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item;

class FormularioSubastas extends Formulario
{
    private $item;
    private $id_usuario_subasta;

    public function __construct($item, $id_usuario_subasta)
    {
        parent::__construct('formSubastas', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./subastas.php'),
        ]);
        $this->item = $item;
        $this->id_usuario_subasta = $id_usuario_subasta;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS

        <button class="btn" type="submit" name="subastar">Subastar</button>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        if (isset($datos['subastar'])) {
            Item::subastarItem($this->item, $this->id_usuario_subasta);
        }
    }

    
}
?>