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
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $correoElectronico = $datos['correoElectronico'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'password', 'password2', 'correoElectronico'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
    $htmlErroresGlobales
    <fieldset>
        <legend>Datos para el registro</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
            {$erroresCampos['nombreUsuario']}
        </div>
        <div>
            <label for="correoElectronico">Correo electrónico:</label>
            <input id="correoElectronico" type="text" name="correoElectronico" value="$correoElectronico" />
            {$erroresCampos['correoElectronico']}
        </div>
        <div>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" />
            {$erroresCampos['password']}
        </div>
        <div>
            <label for="password2">Reintroduce el password:</label>
            <input id="password2" type="password" name="password2" />
            {$erroresCampos['password2']}
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

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombreUsuario || mb_strlen($nombreUsuario) < 3) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 3 caracteres.';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$password || mb_strlen($password) < 5) {
            $this->errores['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password2 = trim($datos['password2'] ?? '');
        $password2 = filter_var($password2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$password2 || $password != $password2) {
            $this->errores['password2'] = 'Los passwords deben coincidir';
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::buscaUsuario($nombreUsuario);

            if ($usuario) {
                $this->errores[] = "El usuario ya existe";
            } else {
                $usuario = Usuario::crea($nombreUsuario, $password, Usuario::USER_ROLE, 50, 500);
                $app = Aplicacion::getInstance();
                $app->login($usuario);
            }
        }
    }
}
