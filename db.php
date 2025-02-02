<?php
// db.php
session_start();

$host = "localhost";    // lub inny host
$db_name = "cms_db";    // nazwa Twojej bazy
$username = "root";     // nazwa użytkownika do bazy
$password = "";         // hasło do bazy

try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/cms_database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
    exit;
}
