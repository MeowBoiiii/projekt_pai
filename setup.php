<?php
// setup.php
global $pdo; // Użycie zmiennej $pdo z db.php

try {
    // Tworzenie tabeli, jeśli nie istnieje
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        summary TEXT,
        content TEXT NOT NULL,
        layout TEXT NOT NULL,
        image_path TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die("Błąd przy tworzeniu tabeli: " . $e->getMessage());
}
?>
