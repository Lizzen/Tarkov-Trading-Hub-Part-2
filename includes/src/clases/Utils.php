<?php
namespace es\ucm\fdi\aw\clases;
abstract class Utils
{
    private function __construct()
    {
    }

    public static function paginaError($codigoRespuesta, $tituloPagina, $mensajeError, $explicacion = '')
    {
        http_response_code($codigoRespuesta);
        $contenidoPrincipal = "<h1>{$mensajeError}</h1><p>{$explicacion}</p>";
        require dirname(__DIR__).'/vistas/comun/layout.php';
        exit();    
    }
    
    public static function redirige($url) 
    {
        header('Location: '.$url);
        exit();
    }
    
    public static function buildUrl($relativeURL, $params = [])
    {
        if ($relativeURL[0] != '/') {
            $relativeURL = '/'.$relativeURL;
        }
        $url = RUTA_APP.$relativeURL;
        $query = self::buildParams($params);
        if (!empty($query)) {
            $url .= '?'.$query;
        }
    
        return $url;
    }
    
    public static function buildParams($params, $separator='&', $enclosing='') {
        $query= '';
        $numParams = 0;
        foreach($params as $param => $value) {
            if ($value != null) {
                if ($numParams > 0) {
                    $query .= $separator;
                }
                $query .= "{$param}={$enclosing}{$value}{$enclosing}";
                $numParams++;
            }
        }
        return $query;
    }
}
function buildFormularioLogin($username = '', $password = '') {
    return <<<EOS
    <form id="formLogin" action="procesarLogin.php" method="POST">
    <fieldset>
        <legend>Usuario y contraseña</legend>
        <div><label>Name:</label> <input type="text" name="username" value="$username" /></div>
        <div><label>Password:</label> <input type="password" name="password" password="$password" /></div>
        <div><button type="submit">Entrar</button></div>
    </fieldset>
    </form>
    EOS;
}
function buildFormularioRegistro($nombreusuario = '', $password = '', $password2 = '' ) {
    $html = <<<EOS
    <form id="formRegistro" action="procesarRegistro.php" method="POST">
    <fieldset>
        <legend>Datos para el registro</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreusuario"/>
        </div>
        <div>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" password="$nombreusuario"/>
        </div>
        <div>
            <label for="password2">Reintroduce el password:</label>
            <input id="password2" type="password" name="password2" password="$nombreusuario"/>
        </div>
        <div>
            <button type="submit" name="registro">Registrar</button>
        </div>
    </fieldset>
    </form>
    EOS;
    return $html;
}
