// funciones.js

window.onload = function () {
  const form = document.getElementById("miFormulario");
  const metodoSelect = document.getElementById("metodo");

  form.addEventListener("submit", function (event) {
    // Kullanıcının seçtiği yöntemi al
    const metodo = metodoSelect.value;

    // Formun gönderme metodunu değiştir
    form.method = metodo;

    // Sadece kontrol için console'a yazalım
    console.log("Formulario enviado con método:", metodo);
  });
};
