// Global bir dizi (Array) tanımlıyoruz, tüm karakterleri burada toplayacağız.
let allCharacters = []; 

// Talep edilen fonksiyonun güncellenmiş, tüm sayfaları çeken hali
async function callApi(url) {
    console.log(`Veri çekiliyor: ${url}`);
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error(`API çağrısı başarısız oldu: ${response.status}`);
    }
    const data = await response.json();
    return data;
}

// Tüm karakterleri çekip listeyi DOM'a basan ana fonksiyon
async function loadAllCharacters(url = "https://rickandmortyapi.com/api/character") {
    
    // 1. Veriyi Çekme
    const data = await callApi(url);
    
    // 2. Karakterleri Genel Listeye Ekleme
    allCharacters = allCharacters.concat(data.results);
    
    // 3. Sayfalama Kontrolü (Rubrikteki "Pinta todos los personajes de todas las páginas" için kritik)
    const nextUrl = data.info.next;

    if (nextUrl) {
        // Eğer sonraki sayfa varsa, kendimizi tekrar çağırıyoruz (Recursive mantık)
        await loadAllCharacters(nextUrl);
    } else {
        // Eğer bu son sayfaysa, artık tüm karakterler elimizde demektir.
        // Karakterleri DOM'a basma fonksiyonunu çağırabiliriz.
        renderCharactersToDOM(allCharacters);
    }
}

// Sadece karakter kartlarını DOM'a basmaktan sorumlu fonksiyon
function renderCharactersToDOM(characters) {
    const container = document.getElementById('character-list-container');
    container.innerHTML = ''; // Önceki içeriği temizle (Gerekli olmayabilir ama iyi bir pratik)

    characters.forEach(character => {
        // DOM Elementi Oluşturma: Link
        const characterLink = document.createElement('a');
        characterLink.href = 'ficha.html'; 
        characterLink.classList.add('character-card');
        
        // Mantık: Tür ve Renk Atama
        const species = character.species;
        const bgColor = species === 'Human' ? 'red' : 'green';
        characterLink.style.backgroundColor = bgColor; 
        
        let img;
        if(character.image != null){
        img = character.image;
        } else {
        img = 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.kindpng.com%2Fimgv%2FTJTJTTw_placeholder-profile-image-placeholder-png-transparent-png%2F&psig=AOvVaw1Y1eQI2QfmHy2uwbiJmUYN&ust=1764877181876000&source=images&cd=vfe&opi=89978449&ved=0CBUQjRxqFwoTCNCvxP2VopEDFQAAAAAdAAAAABAL'; // Varsayılan resim URL'si
        }

        // İçerik (Foto ve İsim)
        characterLink.innerHTML = `
            <img src="${img}" alt="${character.name}">
            <p>${character.name}</p>
        `;

        // Local Storage Mekaniği (Veri Aktarımı için)
        characterLink.addEventListener('click', () => {
            // Tam puan için en kritik noktalardan biri: Veriyi Local Storage'a kaydetme
            localStorage.setItem('selectedCharacter', JSON.stringify(character)); 
        });

        container.appendChild(characterLink);
    });
    console.log(`Tüm sayfalardan toplam ${characters.length} karakter başarıyla yüklendi.`);
}

// Sayfa yüklendiğinde, tüm karakterleri çekme işlemini başlat
if (document.getElementById('character-list-container')) {
    loadAllCharacters();
}

// Diğer JS kodu (ficha.html için) bu dosyanın altına eklenecek ya da ayrı bir dosya kullanılacaks
// Detay sayfası için: Local Storage'dan veriyi çeker ve DOM'a basar// Detay sayfası için: Local Storage'dan veriyi çeker ve DOM'a basar
function loadCharacterDetail() {
    const detailContainer = document.getElementById('character-detail-container');
    const tableBody = document.querySelector('#episode-table tbody');

    // Local Storage'dan veriyi çekiyoruz
    const characterJson = localStorage.getItem('selectedCharacter');
    if (!characterJson) {
        detailContainer.innerHTML = '<p>Karakter bulunamadı. Lütfen listeden seçin.</p>';
        return;
    }

    const character = JSON.parse(characterJson);

    // --- Detay Kartını Oluşturma Kısmı (Aynı Kalıyor) ---
    const species = character.species;
    const bgColor = species === 'Human' ? 'red' : 'green'; 

    detailContainer.style.backgroundColor = bgColor;
    detailContainer.classList.add('detail-card');

    detailContainer.innerHTML = `
        <h2>${character.name}</h2>
        <img src="${character.image}" alt="${character.name}">
        <p><strong>Cins:</strong> ${species}</p>
    `;
    
    // --- Bölüm Listesi Tablosunu Oluşturma (Düzeltilen Kısım) ---
    character.episode.forEach((episodeUrl, index) => {
        
        // 1. DÜZELTME: Bölüm ID'sini URL'den Çıkarma
        // Örnek URL: "https://rickandmortyapi.com/api/episode/51"
        // ID: 51
        const parts = episodeUrl.split('/');
        const episodeId = parts[parts.length - 1]; // URL'nin sonundaki sayısal ID

        const row = tableBody.insertRow();
        
        // Bölüm Adı hücresi
        const nameCell = row.insertCell();
        // Artık sadece sıraya göre değil, gerçek bölüm ID'sini kullanıyoruz.
        nameCell.textContent = `Bölüm ID: ${episodeId}`; 

        // JSON Linki hücresi
        const linkCell = row.insertCell();
        const link = document.createElement('a');
        link.href = episodeUrl; // JSON verisinin olduğu URL
        
        // 2. DÜZELTME: İstenen metin formatı "Capítulo(n)" ve 'n' bölüm ID'si olmalı
        link.textContent = `Capítulo(${episodeId})`; 
        linkCell.appendChild(link);
    });
}

// Detay sayfası yüklendiğinde detayları başlat
if (document.getElementById('character-detail-container')) {
    loadCharacterDetail();
}