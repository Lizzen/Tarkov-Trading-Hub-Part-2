
function actualizaDatos()
{
    $.ajax({
        url: "./controller.php",
        type: "POST",
        data: {
        identifier: "actualizaTiempoRestante",
        },
        success: function (response) {
        console.log("Tiempo restante actualizado correctamente");
        },
        error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error al actualizar el tiempo restante de las subastas: " + textStatus +", " + errorThrown);
        }
    });
}

setInterval(actualizaDatos, 60000);
