//$(document).ready(function () {
  // Selecciona todos los summary de los detalles
  const summaries = document.querySelectorAll("details summary");

  // Agrega el evento click a cada summary
  summaries.forEach((summary) => {
    summary.addEventListener("click", () => {
      // Recorre todos los detalles y cierra aquellos que estén abiertos
      summaries.forEach((s) => {
        if (s !== summary && s.parentElement.hasAttribute("open")) {
          s.parentElement.removeAttribute("open");
        }
      });
    });
  });
//});

/*----------------------------------------------------------
  ----Función sidebar despegable totalmente funcional----
  ----------------------------------------------------------

// Seleccionar la imagen y los summaries
const toggleSummaries = document.getElementById('toggleSummaries');
const toggles = document.getElementsByTagName('summary');
const inventario = document.getElementById('inventario');
const sidebar = document.getElementById("sidebar");

// Al hacer clic en la imagen, se ocultan o muestran todos los summaries
toggleSummaries.addEventListener('click', function() {
  for (let i = 0; i < toggles.length; i++) {
    if (toggles[i].style.display === 'none') {
      toggles[i].style.display = 'block';
      inventario.style.display = 'block';
      sidebar.style.width = "12%";
    } else {
      toggles[i].style.display = 'none';
      inventario.style.display = 'none';
      sidebar.style.width = "5%";
    }
  }
});
*/