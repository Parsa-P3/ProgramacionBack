function anadir(){
    // Obtener los valores de los campos de entrada
    let titulo = document.getElementById("NuevoTitulo").value;
    let autor = document.getElementById("NuevoAutor").value;
    let estado = document.getElementById("NuevoEstado").value;
    let localizacion = document.getElementById("NuevoLocalizacion").value;
    let prestado = document.getElementById("NuevoPrestado").checked;

    // Validar que los campos obligatorios no estén vacíos
    if(titulo.trim() === "" || autor.trim() === "" || localizacion.trim() === ""){
        alert("Por favor, rellena todos los campos obligatorios.");
        return; // Salir de la función si hay campos vacíos
    }

    // Asignar los valores a los campos ocultos del formulario
    document.getElementById("titulo").value = titulo;
    document.getElementById("autor").value = autor;
    document.getElementById("estado").value = estado;
    document.getElementById("localizacion").value = localizacion;
    document.getElementById("prestado").value = prestado ? "true" : "false";

    // Enviar el formulario a la accion de anadir()
    let myform = document.getElementById("myform");
    myform.action = "funciones.php?accion=anadir";
    myform.submit();
}

function modificar(id){
    // Obtener los valores de los campos de entrada
    let titulo = document.getElementById("titulo" + id).value;
    let autor = document.getElementById("autor" + id).value;
    let estado = document.getElementById("estado" + id).value;
    let localizacion = document.getElementById("localizacion" + id).value;
    let prestado = document.getElementById("prestado" + id).checked;

    // Escribir en los campos ocultos del formulario
    document.getElementById("titulo").value = titulo;
    document.getElementById("autor").value = autor;
    document.getElementById("estado").value = estado;
    document.getElementById("localizacion").value = localizacion;
    document.getElementById("prestado").value = prestado ? "true" : "false";
    document.getElementById('id').value = id;

    // Enviar el formulario a la accion de modificar()
    let myform = document.getElementById("myform");
    myform.action = "funciones.php?accion=modificar";
    myform.submit();
}

function eliminar(id){
    let titulo = document.getElementById("titulo" + id).value;
    let salida= confirm("¿Seguro que quieres eliminar comic: " + titulo + "?");
    if(salida){
        // Escribir en los campos ocultos del formulario
        document.getElementById("titulo").value = titulo;
        document.getElementById('id').value = id;

        // Enviar el formulario a la accion de eliminar()
        let myform = document.getElementById("myform");
        myform.action = "funciones.php?accion=eliminar";
        myform.submit();
    }
}

function filtrar(){
    // ⬅️ CORRECCIÓN: Obtener los valores de los 5 filtros
    let tituloFiltro = document.getElementById("ftitulo").value;
    let autorFiltro = document.getElementById("fautor").value;
    let estadoFiltro = document.getElementById("festado").value;
    let localizacionFiltro = document.getElementById("flocalizacion").value;
    let prestadoFiltro = document.getElementById("fprestado").checked;
    
    // Asignar los valores a los campos ocultos del formulario (input hidden)
    document.getElementById('titulo').value = tituloFiltro;
    document.getElementById('autor').value = autorFiltro;
    document.getElementById('estado').value = estadoFiltro;
    document.getElementById('localizacion').value = localizacionFiltro; // ⬅️ NUEVO: Asigna Localización
    
    // Si está marcado, envía "true", si no, envía cadena vacía para ignorar el filtro.
    document.getElementById('prestado').value = prestadoFiltro ? "true" : ""; 

    let myform = document.getElementById("myform");
    myform.action = "index.php"; // Volvemos a index.php para mostrar los resultados
    myform.submit();
}