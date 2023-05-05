<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\Item_subastas;

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
        <div><label>Precio de subasta:</label>
        <input type="number" min="1" max="9999" step="0.01" name="precioSubasta" required/></div>
        <button class="btn" type="submit" name="subastar">Subastar</button>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        $precioSubasta = htmlspecialchars(trim(strip_tags($datos['precioSubasta']))) ?? '';

        if (!filter_var($precioSubasta, FILTER_VALIDATE_FLOAT)) {
            alert('El precio debe ser un número válido.');
            return false;
        }

        $precioSubasta = floatval($precioSubasta);

        if ($precioSubasta < 1 || $precioSubasta > 9999) {
            alert('El precio debe estar entre 1 y 9999.');
            return false;
        }

        if (isset($datos['subastar'])) {
            Item_subastas::subastarItem($this->item, $this->id_usuario_subasta, $precioSubasta);
        }
    }
}
?>