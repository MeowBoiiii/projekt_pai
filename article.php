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
    <link rel="stylesheet" href="style.css" />
    <style>
        .top-image img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }
        .image-float img {
            float: left;
            margin: 0 15px 10px 0;
            max-width: 40%;
            height: auto;
        }
        .bottom-image img {
            display: block;
            margin: 15px auto;
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }
        .background_image { /* Poprawiona klasa tła */
            background-size: cover;
            background-position: center;
            color: white;
            padding: 50px;
            position: relative;
        }
        .background_image p {  /* Styl dla tekstu w tle */
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <h1><?php echo htmlspecialchars($article['title']); ?></h1>
    <p><em>Dodano: <?php echo $article['created_at']; ?></em></p>

    <div class="article 
    <?php 
        if ($layout === 'top_image') {
            echo 'top-image';
        } elseif ($layout === 'image_float') {
            echo 'image-float';
        } elseif ($layout === 'bottom_image') {
            echo 'bottom-image'; // Poprawiona klasa dla zdjęcia na dole
        } elseif ($layout === 'background_image') {
            echo 'background_image'; // Poprawiona klasa tła
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

    <div class="jasniejszydiv">
        <p><a href="index.php">Powrót do listy artykułów</a></p>
    </div>

</body>
</html>
