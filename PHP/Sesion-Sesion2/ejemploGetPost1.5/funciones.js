const NOMBRE_LS = 'peliculasLS'; // Nombre de la clave en LocalStorage

// Función que se ejecuta al cargar la página (body onload)
function cargarPeliculas() {
    // Intentamos cargar la lista de películas desde LocalStorage
    let peliculas = obtenerPeliculasLS();
    // Mostramos todas las películas
    mostrarPeliculas(peliculas);
}

// === FUNCIONES DE LOCALSTORAGE ===

function obtenerPeliculasLS() {
    // Carga las películas guardadas o devuelve un array vacío si no existe
    const data = localStorage.getItem(NOMBRE_LS);
    return data ? JSON.parse(data) : [];
}

function guardarPeliculasLS(peliculas) {
    // Guarda el array completo en LocalStorage
    //stringify convierte un valor de js a un string de json
    localStorage.setItem(NOMBRE_LS, JSON.stringify(peliculas));
}

// === FUNCIONES DE GENERACIÓN DE ID ===

function generarId(peliculas) {
    // Busca el ID máximo existente para generar el siguiente
    if (peliculas.length === 0) return 1;
    const maxId = peliculas.reduce((max, p) => (p.id > max ? p.id : max), 0);
    return maxId + 1;
}

// === FUNCIONES DE DIBUJO Y FILTRADO ===

function mostrarPeliculas(peliculasAMostrar) {
    const listadoDiv = document.getElementById('listado');
    let htmlContent = '';

    // Iteramos sobre el array de películas y creamos el HTML de cada fila
    peliculasAMostrar.forEach(p => {
        const checked = p.vista ? 'checked' : '';
        const filaHtml = `
            <div class="row grid-6-cols" id="row-${p.id}">
                <div><input type="text" id="titulo${p.id}" value="${p.titulo}" /></div>
                <div><input type="text" id="director${p.id}" value="${p.director}" /></div>
                <div><input type="text" id="anyo${p.id}" value="${p.anyo}" /></div>
                <div> 
                    <select id="genero${p.id}">
                        <option value="accion" ${p.genero === 'accion' ? 'selected' : ''}>Acción</option>
                        <option value="drama" ${p.genero === 'drama' ? 'selected' : ''}>Drama</option>
                        <option value="comedia" ${p.genero === 'comedia' ? 'selected' : ''}>Comedia</option>
                    </select>
                </div>
                <div><input type="checkbox" id="vista${p.id}" ${checked} /></div>
                <div>
                    <input type="button" onclick="modificar(${p.id});" value="MOD" />
                    <input type="button" onclick="eliminar(${p.id});" value="DEL" />
                </div>
            </div>
        `;
        htmlContent += filaHtml;
    });

    listadoDiv.innerHTML = htmlContent;
}

function filtrar() {
    const peliculas = obtenerPeliculasLS();
    const filTitulo = document.getElementById('filTitulo').value.toLowerCase();
    const filGenero = document.getElementById('filGenero').value;

    const peliculasFiltradas = peliculas.filter(p => {
        let esValida = true;

        // Filtro por título
        if (filTitulo !== '' && p.titulo.toLowerCase().indexOf(filTitulo) === -1) {
            esValida = false;
        }

        // Filtro por género
        if (filGenero !== '' && p.genero !== filGenero) {
            esValida = false;
        }

        return esValida;
    });

    mostrarPeliculas(peliculasFiltradas);
}

// === FUNCIONES CRUD ===

function anadir() {
    // 1. Recoger datos
    const titulo = document.getElementById('titulo0').value.trim();
    const director = document.getElementById('director0').value.trim();
    const anyo = document.getElementById('anyo0').value.trim();
    const genero = document.getElementById('genero0').value;
    const vista = document.getElementById('vista0').checked;

    // 2. Validación simple
    if (titulo === '' || director === '' || anyo === '') {
        alert('ERROR: Título, Director y Año son obligatorios.');
        return;
    }

    // 3. Crear nuevo objeto
    const peliculas = obtenerPeliculasLS();
    const newId = generarId(peliculas);
    
    const nuevaPelicula = { 
        id: newId, 
        titulo, 
        director, 
        anyo, 
        genero, 
        vista 
    };

    // 4. Añadir y guardar
    peliculas.push(nuevaPelicula);
    guardarPeliculasLS(peliculas);

    // 5. Limpiar campos de añadir y recargar lista (filtrada si aplica)
    document.getElementById('titulo0').value = '';
    document.getElementById('director0').value = '';
    document.getElementById('anyo0').value = '';
    document.getElementById('genero0').value = 'accion'; // Resetear a la primera opción
    document.getElementById('vista0').checked = false;
    
    filtrar(); // Volvemos a mostrar la lista con los filtros aplicados (si hay)
}

function modificar(id) {
    // 1. Recoger datos de la fila con el ID
    const titulo = document.getElementById(`titulo${id}`).value.trim();
    const director = document.getElementById(`director${id}`).value.trim();
    const anyo = document.getElementById(`anyo${id}`).value.trim();
    const genero = document.getElementById(`genero${id}`).value;
    const vista = document.getElementById(`vista${id}`).checked;

    // 2. Validación
    if (titulo === '' || director === '' || anyo === '') {
        alert('ERROR: Título, Director y Año no pueden estar vacíos.');
        return;
    }
    
    // 3. Cargar y actualizar
    const peliculas = obtenerPeliculasLS();
    const index = peliculas.findIndex(p => p.id === id);

    if (index !== -1) {
        peliculas[index].titulo = titulo;
        peliculas[index].director = director;
        peliculas[index].anyo = anyo;
        peliculas[index].genero = genero;
        peliculas[index].vista = vista;
        
        // 4. Guardar y recargar
        guardarPeliculasLS(peliculas);
        alert(`Película ID ${id} modificada.`);
        filtrar(); // Recargar la lista (si los filtros aún aplican)
    }
}

function eliminar(id) {
    const titulo = document.getElementById(`titulo${id}`).value;
    
    if (confirm(`¿Desea eliminar la película "${titulo}" (ID ${id})?`)) {
        // 1. Cargar y filtrar (eliminar)
        let peliculas = obtenerPeliculasLS();
        peliculas = peliculas.filter(p => p.id !== id);

        // 2. Guardar y recargar
        guardarPeliculasLS(peliculas);
        filtrar();
    }
}