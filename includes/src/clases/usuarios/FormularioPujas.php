<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\Item_subastas;

class FormularioPujas extends Formulario
{
    private $item;
    private $id_usuario_subasta;

    public function __construct($subasta, $id_usuario_subasta)
    {
        parent::__construct('formSubastas', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./subastas.php?id=mis_pujas'),
        ]);
        $this->subasta = $subasta;
        $this->id_usuario_subasta = $id_usuario_subasta;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $precioMinimo = ($this->subasta->getPrecio()) + 1;
        $camposFormulario = <<<EOS
        <div><label>Precio de subasta:</label>
        <input type="number" min="$precioMinimo" max="9999" step="0.01" name="precioSubasta" required/></div>
        <button class="btn" type="submit" name="pujar">Pujar</button>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {   
        if (isset($datos['pujar'])){
            $precioSubasta = htmlspecialchars(trim(strip_tags($datos['precioSubasta']))) ?? '';

            if (!filter_var($precioSubasta, FILTER_VALIDATE_FLOAT)) {
                alert('El precio debe ser un número válido.');
                return false;
            }

            $precioSubasta = floatval($precioSubasta);

            if ($this->subasta->getPrecio() > $precioSubasta){
                alert('La puja debe ser mayor a la anterior.');
                return false;
            }

            Item_subastas::pujarItem($this->subasta->getid(), $precioSubasta, $this->id_usuario_subasta);
        }
    }
}
?>