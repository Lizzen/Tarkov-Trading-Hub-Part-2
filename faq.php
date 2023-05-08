<?php
require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Inicio';
$contenidoPrincipal = <<<EOS
    <!DOCTYPE html>
    <html>
    <head>
        <title>FAQ con Details</title>
    </head>
    <body>
    <h1>FAQ</h1>
    <ul>
        <li>
            <details>
                <summary>¿Que es este sitio?</summary>
                <p>
                    Tarkov Trading Hub es una plataforma en línea para el juego Escape from
                    Tarkov. Ofrece una solución eficiente para gestionar tu inventario y
                    participar en un mercado de comercio con otros jugadores. Con un sistema
                    seguro y fácil de usar, podrás intercambiar objetos con otros jugadores y
                    discutir sobre estrategias de tradeo en un chat comunitario, además podrás
                    participar en subastas organizadas por el sistema de items únicos.
                </p>

            </details>
        </li>
        <li>
            <details>
                <summary>¿Cómo me registro en el sitio?</summary>
                <ul>Para registrarte en el sitio, debes hacer lo siguiente:
                    <li>1. Ir a la página de registro</li>
                    <li>2. Completar el formulario de registro con tus datos personales</li>
                    <li>3. Hacer clic en el botón "Registrarse"</li>
                </ul>
            </details>
        </li>
        <li>
            <details>
                <summary>¿Cómo cambio mi contraseña?</summary>
                <ul>Para cambiar tu contraseña, sigue estos pasos:
                    <li>1. Inicia sesión en el sitio</li>
                    <li>2. Ve a tu perfil</li>
                    <li>3. Haz clic en el botón "Cambiar contraseña"</li>
                    <li>4. Ingresa tu contraseña actual y la nueva contraseña</li>
                    <li>5. Haz clic en el botón "Guardar cambios"</li>
                </ul>
            </details>
        </li>
        <li>
            <details>
                <summary>¿Cómo elimino mi cuenta?</summary>
                <ul>Para eliminar tu cuenta, sigue estos pasos:
                    <li>1. Inicia sesión en el sitio</li>
                    <li>2. Ve a tu perfil</li>
                    <li>3. Haz clic en el botón "Eliminar cuenta"</li>
                    <li>4. Ingresa tu contraseña</li>
                    <li>5. Haz clic en el botón "Eliminar cuenta"</li>
                </ul>
            </details>
        </li>
    </ul>
    </body>

    </html>
  EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_faq.php', $params);
