<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioRegistro extends Formulario
{
    public function __construct()
    {
        parent::__construct('formRegistro', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombreItem = $datos['nombreItem'] ?? '';
        $rareza = $datos['rareza'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreItem', 'rareza', 'altura', 'anchura', 'imagen', 'precioMin'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
    $htmlErroresGlobales
    <fieldset>
        <legend>Datos para el registro de un nuevo item</legend>
        <div>
            <label for="nombreItem">Nombre del nuevo item:</label>
            <input id="nombreItem" type="text" name="nombreItem" value="$nombreItem" />
            {$erroresCampos['nombreItem']}
        </div>
        <div>
            <label for="rareza">Rareza del item:</label>
            <input id="rareza" type="text" name="rareza" value="$rareza" />
            {$erroresCampos['rareza']}
        </div>
        <div>
            <label for="altura">Altura del item en el inventario:</label>
            <input id="altura" type="altura" name="altura" />
            {$erroresCampos['altura']}
        </div>
        <div>
            <label for="anchura">Anchura del item en el inventario:</label>
            <input id="anchura" type="anchura" name="anchura" />
            {$erroresCampos['anchura']}
        </div>
        <div>
            <label for="imagen">Imagen del item:</label>
            <input id="imagen" type="imagen" name="imagen" />
            {$erroresCampos['imagen']}
        </div>
        <div>
            <label for="precioMin">Precio minimo del item en venta rapida:</label>
            <input id="precioMin" type="precioMin" name="precioMin" />
            {$erroresCampos['precioMin']}
        </div>
        <div>
            <button type="submit" name="registro">Registrar</button>
        </div>
    </fieldset>
    EOF;
        return $html;
    }



    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombreItem = trim($datos['nombreItem'] ?? '');
        $nombreItem = filter_var($nombreItem, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombreItem || mb_strlen($nombreItem) < 3) {
            $this->errores['nombreItem'] = 'El nombre del item tiene que tener una longitud de al menos 2 caracteres.';
        }

        $rareza = trim($datos['rareza'] ?? '');
        $rareza = filter_var($rareza, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$rareza || mb_strlen($rareza) < 5) {
            $this->errores['rareza'] = 'Debe ser una rareza disponible.';
        }

        $password2 = trim($datos['password2'] ?? '');
        $password2 = filter_var($password2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$password2 || $password != $password2) {
            $this->errores['password2'] = 'Los passwords deben coincidir';
        }

        if (count($this->errores) === 0) {
            $item = Item::buscaItem($nombreItem);

            if ($usuario) {
                $this->errores[] = "El item ya existe";
            } else {
                //$usuario = Item::crea($nombreUsuario, $password, Usuario::USER_ROLE, 50, 500);
                //$app = Aplicacion::getInstance();
                //$app->login($usuario);
            }
        }
    }
}
