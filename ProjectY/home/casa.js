const formatDate = (dateString) => {
    // Formatea la fecha de DD-MM-YYYY a DD/MM/AAAA
    if (!dateString) return 'Desconocida';
    const parts = dateString.split('-');
    if (parts.length === 3) {
        return `${parts[0]}/${parts[1]}/${parts[2]}`;
    }
    return dateString; 
}

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // 1. Obtener los parámetros de la URL
    const params = new URLSearchParams(window.location.search);
    const houseKey = params.get('houseKey'); 
    const houseName = params.get('houseName'); 

    const titleElement = document.getElementById('house-name');
    const container = document.getElementById('characters-container');

    // Actualizar el título y el color de fondo de la página
    if (houseName) {
        document.title = `Personajes de ${houseName}`;
        titleElement.textContent = `Personajes de la Casa ${houseName}`;
        document.body.className = `house-${houseName.toLowerCase()}`; 
    } else {
        titleElement.textContent = "Casa no especificada";
        container.innerHTML = '<p>Por favor, selecciona una casa desde la página principal.</p>';
        return;
    }


    // 2. Obtener los datos del localStorage usando la houseKey
    if (!houseKey) {
        container.innerHTML = '<p>Clave de casa no encontrada en la URL.</p>';
        return;
    }

    const personajesJSON = localStorage.getItem(houseKey);
    let personajes = [];

    if (personajesJSON) {
        try {
            personajes = JSON.parse(personajesJSON);
        } catch (error) {
            console.error("Error al parsear los datos del localStorage:", error);
            container.innerHTML = `<p>Error al cargar los personajes de ${houseName}.</p>`;
            return;
        }
    } else {
        container.innerHTML = `<p>No se encontraron personajes para la Casa ${houseName} en el almacenamiento local.</p>`;
        return;
    }
    
    // 3. ORDENAR PERSONAJES POR FECHA DE NACIMIENTO (los sin fecha van al final)
    personajes.sort((a, b) => {
        // Función auxiliar para parsear la fecha DD-MM-YYYY a un objeto Date
        const parseDate = (dateString) => {
            if (!dateString) return null;
            // La fecha viene como DD-MM-YYYY. La transformamos a YYYY-MM-DD para crear el objeto Date.
            const parts = dateString.split('-');
            if (parts.length === 3) {
                return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
            }
            return null;
        };

        const dateA = parseDate(a.dateOfBirth);
        const dateB = parseDate(b.dateOfBirth);

        // Caso 1: Si ambos no tienen fecha, su orden es el mismo (0)
        if (!dateA && !dateB) {
            return 0;
        }

        // Caso 2: Si solo 'a' no tiene fecha, 'a' va al final (1)
        if (!dateA) {
            return 1;
        }

        // Caso 3: Si solo 'b' no tiene fecha, 'b' va al final (-1)
        if (!dateB) {
            return -1;
        }

        // Caso 4: Ambos tienen fecha, ordenamos del más antiguo (más pequeño) al más reciente
        return dateA.getTime() - dateB.getTime();
    });


    // 4. Renderizar las tarjetas de personajes
    if (personajes.length > 0) {
        let htmlContent = '';
        let imageUrl = '';
        personajes.forEach(personaje => {
            // Manejo de imágenes (mantiene el fallback)
            if (personaje.name === "Severus Snape") {
                 imageUrl = `imgs/perfiles/IMG_7079_1024x1024.jpg`;
            }else{
                 imageUrl = `imgs/perfiles/${personaje.house}.png`;
            }
            

            htmlContent += `
            
            <a href="personaje.html?id=${personaje.id}&houseKey=${houseKey}&houseName=${houseName}" class="card-link-wrapper"> 
            <div class="card">
                <img src="${imageUrl}" class="card-img-top" alt="Retrato de ${personaje.name}" >
                <div class="card-body">
                    <h5 class="card-title">${personaje.name}</h5>
                    <p class="card-text">Casa: ${personaje.house || 'N/A'}</p>
                    <p class="card-text">Nacimiento: ${formatDate(personaje.dateOfBirth)}</p>
                    <p class="card-text">Ascendencia: ${personaje.ancestry || 'Desconocida'}</p>
                    <p class="card-text">Especie: ${personaje.species || 'N/A'}</p>
                </div>
            </div>
            </a>
            `;
        });

        container.innerHTML = htmlContent;

    } else {
        container.innerHTML = `<p>¡Vaya! Parece que no hay personajes registrados para ${houseName}.</p>`;
    }
});

