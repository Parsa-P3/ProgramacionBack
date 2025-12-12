<?php

// Hata Görüntülemeyi AÇIK yap
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// config.php'yi yükler ve $config global değişkenini ayarlar
$config=require_once "engine/config.php";

// Yardımcı fonksiyonları ve çekirdek dosyaları yükler
require_once "engine/encriptador.php";
require_once "error.php";
require_once "sanetizar.php";
require_once "sesion.php";

// Veritabanı bağlantısını kurar ve $pdo global değişkenini ayarlar
require_once "db.php";
$db = new BaseDatos();
$pdo = $db->getPdo();