<?php
require_once __DIR__ . '/includes/config.php';



$tituloPagina = 'Inicio';
$contenidoPrincipal = <<<EOS
  <!DOCTYPE html>
  <html lang="es">
    <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Tarkov Trading Hub</title>
    </head>
  
    <body>
    
      <img src="./css/img/Logo.png" alt="Logo" height="300" class="center" />

      <section>
        <img src = "./css/img/Inventario.png" alt="inventario" onclick="window.location.href='inventario.php'" />
        <img src = "./css/img/Mercado.png" alt="mercado" onclick="window.location.href='mercado.php'" />
        <img src = "./css/img/Subasta.png" alt="subastas" onclick="window.location.href='subastas.php?id=inicio'" />
        <img src = "./css/img/Comunidad.png" alt="comunidad" onclick="window.location.href='comunidad.php'" />
      </section>
      
    </body>
  </html>
  EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('plantillas/plantilla_index.php', $params);
