
// al cargar la pagina :
window.onload = function () {
  const form = document.getElementById("miFormulario");
  const metodoSelect = document.getElementById("metodo");

  form.addEventListener("submit", function (event) {
    // Recibir el metodo elegido por usuario
    const metodo = metodoSelect.value;
    form.method = metodo;
  });
};
