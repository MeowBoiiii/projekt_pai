<?php
require_once 'db.php';

$query = "SELECT name FROM sqlite_master WHERE type='table' AND name='articles'";
$result = $pdo->query($query);

if ($result->fetch()) {
    echo "Tabela 'articles' istnieje.";
} else {
    echo "BŁĄD: Tabela 'articles' nie istnieje!";
}
?>
