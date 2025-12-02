
const detailContainer = document.getElementById('character-detail');
const titleElement = document.getElementById('character-name-title');
const backLink = document.getElementById('back-to-house');

const formatDate = (dateString) => {
    // Formatea la fecha de DD-MM-YYYY a DD/MM/AAAA  
    if (!dateString) return 'Desconocida';
    const parts = dateString.split('-');
    if (parts.length === 3) {
        return `${parts[0]}/${parts[1]}/${parts[2]}`;
    }
    return dateString;
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const characterId = params.get('id');
    const houseKey = params.get('houseKey'); // Obtener la clave de casa
    const houseName = params.get('houseName'); // Obtener el nombre de casa

    // 1. OBTENER DATOS DEL localStorage
    const personajesJSON = localStorage.getItem(houseKey);
    let personaje = null;

    if (personajesJSON) {
        try {
            const personajes = JSON.parse(personajesJSON);
            // 2. BUSCAR EL PERSONAJE POR ID
            personaje = personajes.find(p => p.id === characterId);
        } catch (error) {
            console.error("Error al parsear los datos del localStorage:", error);
            detailContainer.innerHTML = '<p>Error al procesar la lista de personajes del almacenamiento local.</p>';
            return;
        }
    }

    if (personaje) {
        // 3. MOSTRAR DETALLES

        // Actualizar título y enlace de volver
        
        document.title = personaje.name;
        
        // Configurar el enlace de volver a casa.html
        backLink.href = `casa.html?houseKey=${houseKey}&houseName=${houseName}`;
        backLink.textContent = `Volver a la Casa ${houseName || 'Hogwarts'}`;
        backLink.id = 'back-link';


        // Construir el HTML de la vista detallada
            let imageUrl = '';

        if (personaje.name === "Severus Snape") {
                 imageUrl = `imgs/perfiles/IMG_7079_1024x1024.jpg`;
            }else{
                 imageUrl = `imgs/perfiles/${personaje.house}.png`;
            }
        detailContainer.innerHTML = `
            <div class="detail-card">
                <img src="${imageUrl}" class="detail-img" alt="Retrato de ${personaje.name}" >
                <div class="detail-info">
                    <h2>${personaje.name}</h2>
                    <p><strong>Casa:</strong> ${personaje.house || 'N/A'}</p>
                    <p><strong>Especie:</strong> ${personaje.species || 'N/A'}</p>
                    <p><strong>Género:</strong> ${personaje.gender || 'N/A'}</p>
                    <p><strong>Fecha de Nacimiento:</strong> ${formatDate(personaje.dateOfBirth)}</p>
                    <p><strong>Ascendencia:</strong> ${personaje.ancestry || 'Desconocida'}</p>
                    <p><strong>Patronus:</strong> ${personaje.patronus || 'Desconocido'}</p>
                    <p><strong>Actor:</strong> ${personaje.actor || 'N/A'}</p>
                    <p><strong>Estudiante de Hogwarts:</strong> ${personaje.hogwartsStudent ? 'Sí' : 'No'}</p>
                    <p><strong>Personal de Hogwarts:</strong> ${personaje.hogwartsStaff ? 'Sí' : 'No'}</p>
                </div>
            </div>
        `;
        
    } else {
        titleElement.textContent = "Personaje No Encontrado";
        detailContainer.innerHTML = '<p>El personaje no fue encontrado en el almacenamiento local.</p>';
    }
});