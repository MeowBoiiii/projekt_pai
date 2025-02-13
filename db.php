<?php
// db.php
session_start();

$host = "localhost";    // lub inny host
$db_name = "cms_db";    // nazwa Twojej bazy
$username = "root";     // nazwa użytkownika do bazy
$password = "";         // hasło do bazy

try {
    // Połączenie z bazą SQLite
    $pdo = new PDO("sqlite:" . __DIR__ . "/cms_database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

// Wczytaj plik setup.php, który utworzy tabelę (jeśli nie istnieje)
require_once 'setup.php';
?>
