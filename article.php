<?php
// article.php
require_once 'db.php';

$id = $_GET['id'] ?? 0;
$id = (int)$id;

// Pobierz artykuł z bazy
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    // Jeśli brak artykułu w bazie
    echo "Artykuł nie istnieje!";
    exit;
}

// Rozpoznanie layoutu
$layout = $article['layout'];
$image_path = $article['image_path'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <h1><?php echo htmlspecialchars($article['title']); ?></h1>
    <p><em>Dodano: <?php echo $article['created_at']; ?></em></p>

    <div class="
        <?php 
            if ($layout === 'top_image') {
                echo 'top-image';
            } elseif ($layout === 'image_float') {
                echo 'image-float';
            } else {
                echo 'no-image';
            }
        ?>
    ">
        <?php if (!empty($image_path) && file_exists($image_path)): ?>
            <img src="<?php echo $image_path; ?>" alt="Obrazek do artykułu" />
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
    </div>

    <p><a href="index.php">Powrót do listy artykułów</a></p>
</body>
</html>
