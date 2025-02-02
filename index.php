<?php
// index.php
require_once 'db.php';

// Pobierz wszystkie artykuły (tytuł i streszczenie)
$stmt = $pdo->query("SELECT id, title, summary FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Lista artykułów</title>
</head>
<body>
    <h1>Lista artykułów</h1>
    <?php foreach ($articles as $article): ?>
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($article['summary'])); ?></p>
            <p><a href="article.php?id=<?php echo $article['id']; ?>">Czytaj więcej</a></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
