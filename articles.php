<?php
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM articles ORDER BY id DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie artykułami</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-panel">
        <h1>Zarządzanie artykułami</h1>
        <p><a href="admin_panel.php">Dodaj nowy artykuł</a> | <a href="logout.php">Wyloguj</a></p>

        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                <p><?php echo htmlspecialchars($article['summary']); ?></p>
                <p><a href="edit_article.php?id=<?php echo $article['id']; ?>">Edytuj</a> | 
                   <a href="delete_article.php?id=<?php echo $article['id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten artykuł?');">Usuń</a></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
