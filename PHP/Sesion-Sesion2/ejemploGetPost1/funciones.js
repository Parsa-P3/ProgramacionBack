function anadir(){
    // 1. Recoger datos del formulario de adición
    let titulo=document.getElementById('titulo0').value;
    let autor=document.getElementById('autor0').value;
    let estado=document.getElementById('estado0').value;
    let localizacion=document.getElementById('localizacion0').value;
    let prestado=document.getElementById('prestado0').checked; // Estado del Checkbox

    // 2. Validación de entradas vacías (Título, Autor, Localización)
    if(titulo.trim() === '' || autor.trim() === '' || localizacion.trim() === ''){
        alert('ERROR: Los campos Título, Autor y Estante no pueden estar vacíos.');
        return; // Detiene la función y no envía el formulario
    }

    // 3. Escribir en los campos ocultos (Para enviar por POST)
    document.getElementById('titulo').value=titulo;
    document.getElementById('autor').value=autor;
    document.getElementById('estado').value=estado;
    document.getElementById('localizacion').value=localizacion;
    document.getElementById('prestado').value=prestado ? 'true' : 'false'; // Enviar como string 'true'/'false'

    // 4. Enviar el formulario a la acción de añadir
    let frm =document.getElementById('frm');
    frm.action='funciones.php?action=anadir'; 
    frm.submit();
}

function modificar(id){
    // 1. Recoger datos de la fila específica (por ID)
    let titulo=document.getElementById('titulo'+ id).value;
    let autor=document.getElementById('autor'+ id).value;
    let estado=document.getElementById('estado'+ id).value;
    let localizacion=document.getElementById('localizacion'+ id).value;
    let prestado=document.getElementById('prestado'+ id).checked;

    // 2. Escribir en los campos ocultos
    document.getElementById('titulo').value=titulo;
    document.getElementById('autor').value=autor;
    document.getElementById('estado').value=estado;
    document.getElementById('localizacion').value=localizacion;
    document.getElementById('prestado').value=prestado ? 'true' : 'false';
    document.getElementById('id').value=id; // Enviar el ID

    // 3. Enviar el formulario a la acción de guardar/modificar
    let frm =document.getElementById('frm');
    frm.action='funciones.php?action=guardar'; 
    frm.submit();
}

function eliminar(id){
    let titulo=document.getElementById('titulo'+ id).value;
    let salida=confirm(`Va a eliminar el cómic ${titulo}. ¿Desea continuar?`); // Mensaje de confirmación

    if(salida){
        document.getElementById('id').value=id; // Enviar el ID

        // Enviar el formulario a la acción de eliminar
        let frm =document.getElementById('frm');
        frm.action='funciones.php?action=eliminar'; 
        frm.submit();
    }
}

function filtrar(){
    // 1. Recoger datos de los campos de filtro
    let titulo=document.getElementById('filTitulo').value;
    let estado=document.getElementById('filEstado').value;

    // 2. Escribir en los campos ocultos (para que index.php los reciba y filtre)
    document.getElementById('titulo').value=titulo;
    document.getElementById('estado').value=estado;
    
    // 3. Enviar el formulario (se enviará a index.php por defecto, sin acción query)
    let frm =document.getElementById('frm');
    frm.action='index.php'; // Se recarga la página con los filtros
    frm.submit();
}