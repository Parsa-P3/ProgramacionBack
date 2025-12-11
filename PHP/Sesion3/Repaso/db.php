<?php
// require_once "utils.php"; // BU SATIR SİLİNMİŞTİR!
// utils.php zaten bunu yüklediği için, sadece global değişkeni kullanacağız.

class BaseDatos
{
    protected $pdo; 

    public function __construct()
    {
        // $config değişkeni utils.php tarafından zaten global olarak yüklendi.
        global $config; 
        
        $bbdd = $config['database']['dbname'];
        
        // Veritabanı dosyasının yolu
        $dbPath = __DIR__ . '/bbdd/'. $bbdd;

        // DB Bağlantısı
        $this->pdo = new PDO('sqlite:' . $dbPath);
        
        // Hata modu ayarı (gerekli)
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPdo() { return $this->pdo; }
}