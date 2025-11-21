alert("Bienvenido al gestor de comics! Usa la consola o la interfaz HTML para interactuar.");

// Definicion de la clase Comic
class Comic {
    constructor(id, titulo, autor, estado = "pendiente de leer", prestado = false, localizacion = "") {
        this.id = id; // El ID único
        this.titulo = titulo;
        this.autor = autor;
        this.estado = estado; // "pendiente de leer", "leyendo", "leido"
        this.prestado = prestado; // true o false
        this.localizacion = localizacion; // "estanteria1", "mueble", etc.
    }
}

// Simulamos almacenamiento con localStorage
let comics = JSON.parse(localStorage.getItem("comics") || "[]");

// funcion para guardar en localStorage
window.guardar = function () {
    localStorage.setItem("comics", JSON.stringify(comics));
}

// Busca el menor ID positivo no utilizado (busca "huecos").
function generarNuevoID() {
    if (comics.length === 0) {
        return 1;
    }

    const idsExistentes = comics.map(c => c.id).sort((a, b) => a - b);
    let nuevoID = 1;

    for (const id of idsExistentes) {
        if (id > nuevoID) {
            return nuevoID; // Encontró un hueco
        }
        nuevoID = id + 1;
    }

    return nuevoID; // Devuelve el máximo + 1
}


// --- LÓGICA CENTRAL (Funciones de consola/Motor) ---

// Agregar un comic
window.agregarComic = function (titulo, autor, estado, prestado, localizacion) {
    if (!titulo || !autor ) {
        alert(" Debes ingresar todos los campos: titulo, autor, estado, prestado, localizacion.");
        console.log(" Debes ingresar todos los campos.");
        return false;
    }
    // Verificar si ya existe por título
    const existe = comics.some(c => c.titulo.toLowerCase() === titulo.toLowerCase());
    if (existe) {
        console.log(` El comic con titulo "${titulo}" ya existe.`);
        alert(` El comic con titulo "${titulo}" ya existe.`);
        return false;
    }

    // Agregar nuevo comic
    const nuevoId = generarNuevoID();
    // Crear instancia y agregar al array
    const comic = new Comic(nuevoId, titulo, autor, estado, prestado, localizacion);
    // Agregar al array y guardar
    comics.push(comic);
    // Guardar cambios en localStorage
    guardar();
    // Mensaje de confirmacion
    console.log(` Comic agregado: ${titulo} (ID: ${nuevoId})`);
    // Actualizar la UI si es posible
    if (typeof listarComicsDesdeUI === 'function') {
        listarComicsDesdeUI();
    }
    
    return true;
}

// Listar comics (para consola)
window.listarComics = function () {
    console.log(" Lista de comics:");
    // Ordenar por ID antes de mostrar
    comics.sort((a, b) => a.id - b.id);

    // Si no hay comics
    if (comics.length === 0) {
        console.log("No hay comics registrados.");
        return;
    }
    // Recorrer e imprimir
    comics.forEach((c, i) => {
        console.log(
            `${i + 1}. [ID: ${c.id}] "${c.titulo}" - ${c.autor} | Estado: ${c.estado} | ` +
            `Prestado: ${c.prestado ? "Si" : "No"} | Localizacion: ${c.localizacion}`
        );
    });
}

// Eliminar comic (acepta ID o Título)
window.eliminarComic = function (identificador) {
    let index = -1;

    // Buscar por ID o Título
    if (typeof identificador === 'number' && Number.isInteger(identificador)) {
        // Buscar por ID
        index = comics.findIndex(c => c.id === identificador);
    }
    // Buscar por Título (case insensitive) 
    else if (typeof identificador === 'string' && identificador.trim() !== "") {
        // Buscar por Título
        index = comics.findIndex(c => c.titulo.toLowerCase() === identificador.toLowerCase());
    } 
    // Identificador no válido
    else {
        console.log(" Identificador no válido.");
        alert(" Identificador no válido.");
        return;
    }

    // Si no se encontró
    if (index === -1) {
        alert(` No se encontro el comic con identificador "${identificador}".`);
        console.log(` No se encontro el comic con identificador "${identificador}".`);
        return;
    }

    // Eliminar del array
    const tituloEliminado = comics[index].titulo;
    // Obtener ID del comic eliminado
    const idEliminado = comics[index].id;
    // Eliminar
    comics.splice(index, 1);
    // Guardar cambios en localStorage
    guardar();
        // Actualizar la UI si es posible
    if (typeof listarComicsDesdeUI === 'function') {
        listarComicsDesdeUI();
    }
    console.log(` Comic "${tituloEliminado}" (ID: ${idEliminado}) eliminado.`);
}

// Cambiar estado (acepta ID o Título)
window.cambiarEstadoComic = function (identificador, nuevoEstado) {
    let comic = null;

    // Buscar por ID o Título
    if (typeof identificador === 'number' && Number.isInteger(identificador)) {
        // Buscar por ID
        comic = comics.find(c => c.id === identificador);
        
    } else if (typeof identificador === 'string' && identificador.trim() !== "") {
        // Buscar por Título (case insensitive)
        comic = comics.find(c => c.titulo.toLowerCase() === identificador.toLowerCase());
    } else {
        console.log(" Identificador no válido.");
        return;
    }

    // Si no se encontró
    if (!comic) {
        console.log(` No se encontro el comic con identificador "${identificador}".`);
        return;
    }

    // Actualizar estado
    comic.estado = nuevoEstado;

    // Guardar cambios en localStorage
    guardar();
        // Actualizar la UI si es posible
    if (typeof listarComicsDesdeUI === 'function') {
        listarComicsDesdeUI();
    }
    console.log(` Estado de "${comic.titulo}" (ID: ${comic.id}) actualizado a "${nuevoEstado}".`);
}

// Mensaje de ayuda en consola
console.log(" --- GESTOR DE CoMICS --- ");
console.log(" * funciones para consola: *");
console.log(" -> agregarComic(titulo(String), autor(String), estado(String), prestado(Boolean), localizacion(String))");
console.log(" -> listarComics()");
console.log(" -> eliminarComic(identificador: titulo(String) o ID)");
console.log(" -> cambiarEstadoComic(identificador: titulo(String) o ID, nuevoEstado(String))");
console.log("----------------------------------------");







// --------------------------------------------------------
// --- FUNCIONES DE INTERFAZ DE USUARIO (UI) ---
// --------------------------------------------------------






// Listar comics en la UI (ordenados por ID)
window.listarComicsDesdeUI = function () {
    // Obtenemos el contenedor
    const contenedor = document.getElementById('listado-comics');
    // Limpiamos el contenido anterior
    contenedor.innerHTML = ''; 

    // Si no hay comics
    if (comics.length === 0) {
        // Mensaje de no hay comics
        contenedor.innerHTML = 'No hay comics registrados en la coleccion.';
        return;
    }

    // Clonar y ordenar por ID antes de mostrar
    const comicsOrdenados = [...comics].sort((a, b) => a.id - b.id);

    // Recorremos el array ORDENADO
    comicsOrdenados.forEach((c, i) => {
        // Crear elemento para cada comic
        const item = document.createElement('div');
        // Asignar clase CSS
        item.className = 'comic-item';

        // Determinar clase de estado
        let estadoClase = '';
        if (c.estado === 'leido') {
            estadoClase = 'estado-leido';
        } else if (c.estado === 'leyendo') {
            estadoClase = 'estado-leyendo';
        } else {
            estadoClase = 'estado-pendiente';
        }

        // Rellenar contenido HTML
        item.innerHTML = `
            <strong> ${c.titulo}</strong>
            <p>Id: ${c.id}</p>
            <p>Autor: ${c.autor}</p>
            <p>Estado:${c.estado}</p>
            <p>Prestado: ${c.prestado ? ' ¡Prestado!' : ' ¡No Prestado!'}</p>
            <p>Localizacion: ${c.localizacion}</p>
            
        `;
        // Agregar al contenedor
        contenedor.appendChild(item);
    });

    // Mensaje en consola
    console.log(`--- Lista de ${comics.length} comics mostrada en la interfaz (Ordenada por ID) ---`);
}

// Handler para agregar desde el formulario
window.agregarComicDesdeUI = function() {
    // Obtenemos los valores del formulario
    const titulo = document.getElementById('titulo').value.trim();
    const autor = document.getElementById('autor').value.trim();
    const estado = document.getElementById('estado').value;
    const localizacion = document.getElementById('localizacion').value.trim();
    const prestado = document.getElementById('prestado').value === 'true';

    // Agregar comic
    const exito = agregarComic(titulo, autor, estado, prestado, localizacion);

    // Si se agregó con éxito, limpiar el formulario
    if (exito) {
        document.getElementById('titulo').value = '';
        document.getElementById('autor').value = '';
        document.getElementById('localizacion').value = '';
        listarComicsDesdeUI();
    }
}

// Handler para cambiar estado desde el formulario (detecta ID o Título)
window.cambiarEstadoDesdeUI = function() {
    // Obtenemos los valores del formulario
    const inputValor = document.getElementById('modificarTitulo').value.trim();
    const nuevoEstado = document.getElementById('nuevoEstado').value;

    // Validar entrada
    if (!inputValor) {
        alert("Debes ingresar el Título o el ID del cómic a modificar.");
        console.log("Debes ingresar el Título o el ID del cómic a modificar.");
        return;
    }

    // Determinar si es ID (número) o Título (string)
    let identificador;
    // Intentar convertir a número
    const numID = parseInt(inputValor);

    // Si es un número válido y positivo, lo usamos como ID numérico
    if (!isNaN(numID) && numID > 0) {
        identificador = numID; // Es un ID (number)
    } else {
        identificador = inputValor; // Es un Título (string)
    }

    // Cambiar estado
    cambiarEstadoComic(identificador, nuevoEstado);
    // Limpiar campos
    document.getElementById('modificarTitulo').value = ''; 
    // Actualizar lista en UI
    listarComicsDesdeUI();
}

// Handler para eliminar desde el formulario (detecta ID o Título)
window.eliminarComicDesdeUI = function(idAEliminar = null) {
    let identificador = idAEliminar;

    // Si no se pasó un ID, obtener del input
    if (identificador === null) {
        const inputValor = document.getElementById('eliminarIdentificador').value.trim();
        // Intenta convertir a número (para IDs), si falla o es 0, usa el string (para títulos)
        identificador = !isNaN(parseInt(inputValor)) && parseInt(inputValor) > 0 ? parseInt(inputValor) : inputValor;
    }

    // Validar entrada
    if (!identificador) {
        alert("Por favor, ingresa el Título o el ID del cómic a eliminar.");
        console.log("Por favor, ingresa el Título o el ID del cómic a eliminar.");
        return;
    }

    // Confirmar eliminación
    if (confirm(`¿Estás seguro de que deseas eliminar el cómic con identificador "${identificador}"?`)) {
        eliminarComic(identificador);

        // Limpiar campo si se usó el formulario
        if (idAEliminar === null) {
            document.getElementById('eliminarIdentificador').value = '';
        }
        listarComicsDesdeUI();
    }
}


// Mensaje de inicio en consola
console.log(" --- GESTOR DE CoMICS CARGADO --- ");
console.log("Utiliza la interfaz HTML o las funciones globales para interactuar.");