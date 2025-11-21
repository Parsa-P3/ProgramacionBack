function anadir(){
    // 1. Recoger datos del formulario de adición
    let titulo=document.getElementById('titulo0').value;
    let director=document.getElementById('director0').value; // Antes: autor
    let genero=document.getElementById('genero0').value; // Antes: estado
    let plataforma=document.getElementById('plataforma0').value; // Antes: localizacion
    let vista=document.getElementById('vista0').checked; // Antes: prestado

    // 2. Validación de entradas vacías 
    if(titulo.trim() === '' || director.trim() === '' || plataforma.trim() === ''){
        alert('ERROR: Los campos Título, Director y Plataforma no pueden estar vacíos.');
        return; // Detiene la función y no envía el formulario
    }

    // 3. Escribir en los campos ocultos (Para enviar por POST)
    document.getElementById('titulo').value=titulo;
    document.getElementById('director').value=director;
    document.getElementById('genero').value=genero;
    document.getElementById('plataforma').value=plataforma;
    document.getElementById('vista').value=vista ? 'true' : 'false'; 

    // 4. Enviar el formulario a la acción de añadir
    let frm =document.getElementById('frm');
    frm.action='funciones.php?action=anadir'; 
    frm.submit();
}

function modificar(id){
    // 1. Recoger datos de la fila específica (por ID)
    let titulo=document.getElementById('titulo'+ id).value;
    let director=document.getElementById('director'+ id).value;
    let genero=document.getElementById('genero'+ id).value;
    let plataforma=document.getElementById('plataforma'+ id).value;
    let vista=document.getElementById('vista'+ id).checked;

    // 2. Escribir en los campos ocultos
    document.getElementById('titulo').value=titulo;
    document.getElementById('director').value=director;
    document.getElementById('genero').value=genero;
    document.getElementById('plataforma').value=plataforma;
    document.getElementById('vista').value=vista ? 'true' : 'false';
    document.getElementById('id').value=id; 

    // 3. Enviar el formulario a la acción de guardar/modificar
    let frm =document.getElementById('frm');
    frm.action='funciones.php?action=guardar'; 
    frm.submit();
}

function eliminar(id){
    let titulo=document.getElementById('titulo'+ id).value;
    let salida=confirm(`Va a eliminar la película ${titulo}. ¿Desea continuar?`); // Mensaje de confirmación

    if(salida){
        document.getElementById('id').value=id; 

        // Enviar el formulario a la acción de eliminar
        let frm =document.getElementById('frm');
        frm.action='funciones.php?action=eliminar'; 
        frm.submit();
    }
}

function filtrar(){
    // 1. Recoger datos de los campos de filtro
    let titulo=document.getElementById('filTitulo').value;
    let genero=document.getElementById('filGenero').value; // Nuevo filtro

    // 2. Escribir en los campos ocultos
    document.getElementById('titulo').value=titulo;
    document.getElementById('genero').value=genero;
    
    // 3. Enviar el formulario (se recarga la página)
    let frm =document.getElementById('frm');
    frm.action='index.php'; 
    frm.submit();
}