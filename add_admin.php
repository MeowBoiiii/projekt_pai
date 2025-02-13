<?php
require_once 'db.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
$stmt->execute(['username' => $username, 'password' => $password, 'role' => $role]);

echo "Administrator dodany. Login: admin, Hasło: admin123";
