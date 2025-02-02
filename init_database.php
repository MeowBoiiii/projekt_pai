<?php
require_once 'db.php'; // Ładuje połączenie do bazy

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS articles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(255) NOT NULL,
        summary TEXT,
        content TEXT,
        layout VARCHAR(50),
        image_path VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ";
    
    $pdo->exec($sql);
    echo "Tabele zostały utworzone.";
} catch (PDOException $e) {
    echo "Błąd inicjalizacji bazy danych: " . $e->getMessage();
}
