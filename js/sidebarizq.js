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
