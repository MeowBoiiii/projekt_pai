<?php
// index.php
require_once 'db.php';

// Sprawdzenie, czy użytkownik jest zalogowany
session_start();

// Jeśli użytkownik jest zalogowany, pozwól na wylogowanie
if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}

// Pobierz wszystkie artykuły (tytuł i streszczenie)
$stmt = $pdo->query("SELECT id, title, summary FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Lista artykułów</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <h1>Lista artykułów</h1>

    <!-- Przycisk wylogowania, jeśli użytkownik jest zalogowany -->
    <?php if ($isLoggedIn): ?>
        <form method="post" action="logout.php">
            <button type="submit">Wyloguj się</button>
        </form>
    <?php endif; ?>

    <!-- Lista artykułów -->
    <?php foreach ($articles as $article): ?>
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($article['summary'])); ?></p>
            <p><a href="article.php?id=<?php echo $article['id']; ?>">Czytaj więcej</a></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
