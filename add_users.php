<?php
require_once 'db.php';

// Hasła
$passwordAdmin = password_hash('admin123', PASSWORD_DEFAULT);
$passwordUser = password_hash('user123', PASSWORD_DEFAULT);

// Przygotowanie zapytania SQL do dodania użytkowników
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute(['admin', $passwordAdmin, 'admin']);
$stmt->execute(['user', $passwordUser, 'user']);

echo "Użytkownicy zostali dodani!";
?>
