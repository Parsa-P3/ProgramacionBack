const url = "https://hp-api.onrender.com/api/characters";


async function obtenerDatos() {

    const divSlytherin = document.getElementById("divSlytherin");
    const divGryffindore = document.getElementById("divGryffindore");
    const divRavenclaw = document.getElementById("divRavenclaw");
    const divHufflepuff = document.getElementById("divHufflepuff");

    // Limpiar el contenido inicial antes de cargar
    divGryffindore.innerHTML = '';
    divSlytherin.innerHTML = '';
    divRavenclaw.innerHTML = '';
    divHufflepuff.innerHTML = '';

    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        // 1. Filtrar los personajes por casa
        const personajesGryffindore = datos.filter(personaje => personaje.house === "Gryffindor");
        const personajesSlytherin = datos.filter(personaje => personaje.house === "Slytherin");
        const personajesHufflepuff = datos.filter(personaje => personaje.house === "Hufflepuff");
        const personajesRavenclaw = datos.filter(personaje => personaje.house === "Ravenclaw");

        // 2. Guardar en localStorage (Clave y Nombre para casa.html)
        localStorage.setItem("personajesGryffindore", JSON.stringify(personajesGryffindore));
        localStorage.setItem("personajesSlytherin", JSON.stringify(personajesSlytherin));
        localStorage.setItem("personajesHufflepuff", JSON.stringify(personajesHufflepuff));
        localStorage.setItem("personajesRavenclaw", JSON.stringify(personajesRavenclaw));

            

            // GRYFFINDOR
            divGryffindore.innerHTML = `
            <a href="casa.html?houseKey=personajesGryffindore&houseName=Gryffindor" class="house-link">
            </a>
            <div class="side-text-container left-side"> {/* <-- NUEVO CONTENEDOR DE TEXTO */}
                <h2>Gryffindor</h2>
                <p>La Casa que valora el **coraje** y la **osadía**.</p>
                <p class="description">Fundada por Godric Gryffindor. Su animal emblemático es el león. <br>¡Entra si te atreves!</p>
            </div>`;


            // SLYTHERIN
            divSlytherin.innerHTML = `
            <a href="casa.html?houseKey=personajesSlytherin&houseName=Slytherin" class="house-link">
            </a>
            <div class="side-text-container right-side"> {/* <-- NUEVO CONTENEDOR DE TEXTO, CLASE 'right-side' */}
                <h2>Slytherin</h2>
                <p>La Casa que valora la **ambición** y la **astucia**.</p>
                <p class="description">Fundada por Salazar Slytherin. Su animal emblemático es la serpiente. <br>Solo para los de pura sangre.</p>
            </div>`;

            // RAVENCLAW
            divRavenclaw.innerHTML = `
            <a href="casa.html?houseKey=personajesRavenclaw&houseName=Ravenclaw" class="house-link">
            </a>
            <div class="side-text-container right-side"> {/* <-- NUEVO CONTENEDOR DE TEXTO, CLASE 'right-side' */}
                <h2>Ravenclaw</h2>
                <p>La Casa que valora la **inteligencia** y el **aprendizaje**.</p>
                <p class="description">Fundada por Rowena Ravenclaw. Su animal emblemático es el águila. <br>El saber es poder.</p>
            </div>`;

            // HUFFLEPUFF
            divHufflepuff.innerHTML = `
            <a href="casa.html?houseKey=personajesHufflepuff&houseName=Hufflepuff" class="house-link">
            </a>
            <div class="side-text-container left-side"> {/* <-- NUEVO CONTENEDOR DE TEXTO, CLASE 'left-side' */}
                <h2>Hufflepuff</h2>
                <p>La Casa que valora el **trabajo duro** y la **lealtad**.</p>
                <p class="description">Fundada por Helga Hufflepuff. Su animal emblemático es el tejón. <br>La justicia ante todo.</p>
            </div>`;

    } catch (error) {
        console.error("Error al obtener o procesar los datos de la API: ", error);
    }

    
};
obtenerDatos();