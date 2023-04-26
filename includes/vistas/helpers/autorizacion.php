<?php
use es\ucm\fdi\aw\clases\Utils;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

function estaLogado()
{
    return isset($_SESSION['idUsuario']);
}


function esMismoUsuario($idUsuario)
{
    return estaLogado() && $_SESSION['idUsuario'] == $idUsuario;
}

function idUsuarioLogado()
{
    return $_SESSION['idUsuario'] ?? false;
}

function esAdmin()
{
    return estaLogado() && $_SESSION['rol'] == "1";
}

function verificaLogado($urlNoLogado)
{
    if (! estaLogado()) {
        Utils::redirige($urlNoLogado);
    }
}
