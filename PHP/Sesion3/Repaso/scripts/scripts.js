// scripts.js (Tüm projenin JavaScript'i)

// Genel Navigasyon Fonksiyonları
function IrFicha(list = false) {
    let qList = "";
    if (list) {
        qList = "?listado=true";
    }
    let frm = document.forms[0];
    frm.action = `ficha.php${qList}`;
    frm.submit();
}

function IrLogin() {
    let frm = document.forms[0];
    frm.action = `login.php`;
    frm.submit();
}

function login() {
    let frm = document.forms[0];
    frm.action = `acceder.php?action=login`;
    frm.submit();
}

// Kullanıcı Yönetimi Fonksiyonları
function anadirUsuario() {
    let frm = document.getElementById('frmUsuario');
    frm.action = `ficha_guardar.php?action=anadir`;
    frm.submit();
}

function modificarUsuario() {
    let frm = document.getElementById('frmUsuario');
    frm.action = `ficha_guardar.php?action=guardar`;
    frm.submit();
}

function eliminarUsuario(id) {
    if (confirm("Bu kullanıcıyı ve ona ait tüm iletişimleri silmek istediğinizden emin misiniz?")) {
        let frm = document.getElementById('frmEli');
        let id_field = document.getElementById('usuario_id_sil');
        
        id_field.value = id;
        frm.submit();
    }
}

// İletişim Yönetimi Fonksiyonları (YENİ)
function guardarContacto(action) {
    let frm = document.getElementById('frmContacto');
    
    // Client-side Telefon Validasyonu (İspanya formatı: 9 basamaklı, 6, 7, 8 veya 9 ile başlayan)
    let telefono = document.getElementById('telefono').value;
    if (!/^[6-9]{1}[0-9]{8}$/.test(telefono)) {
        alert("Telefon numarası hatalı. İspanyol numarası (9 basamaklı) bekleniyor.");
        return false;
    }
    
    frm.action = `contacto_guardar.php?action=${action}`;
    frm.submit();
}

function eliminarContacto(id) {
    if (confirm("Bu iletişimi silmek istediğinizden emin misiniz?")) {
        let frm = document.getElementById('frmEliContacto');
        let id_field = document.getElementById('contacto_id_sil');
        
        id_field.value = id;
        frm.submit();
    }
}