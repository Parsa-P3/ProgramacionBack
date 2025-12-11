<?php
// $_POST ve $_GET verilerini XSS saldırılarına karşı temizler

foreach ($_POST as $clave => $valor) {
    // HTML etiketlerini ve özel karakterleri temizler
    $_POST[$clave]=strip_tags(htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'));
}

foreach ($_GET as $clave => $valor) {
    $_GET[$clave]=strip_tags(htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'));
}