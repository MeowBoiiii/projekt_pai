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
        .article {
            padding: 20px;
            border-radius: 8px;
            max-width: 800px;
            margin: 20px auto;
            color: white;
            background: #34495e;
        }

        /* Styl dla obrazu na górze */
        .top-image img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }

        /* Styl dla obrazu opływającego tekst */
        .image-float img {
            float: left;
            margin: 0 15px 10px 0;
            max-width: 40%;
            height: auto;
        }

        /* Zapewnia, że tekst nie zawija się pod zdjęciem */
        .image-float::after {
            content: "";
            display: block;
            clear: both;
        }

        /* Styl dla obrazu na dole */
        .bottom-image {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .bottom-image img {
            max-width: 100%;
            height: auto;
            margin-top: 15px;
            border-radius: 8px;
            order: 2;
        }

        .bottom-image p {
            order: 1;
        }

        /* Styl dla artykułu z tłem */
        .background_image {
            background-size: cover;
            background-position: center;
            padding: 50px;
            color: white;
            position: relative;
        }

        .background_image p {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            display: inline-block;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1><?php echo htmlspecialchars($article['title']); ?></h1>
    <p><em>Dodano: <?php echo $article['created_at']; ?></em></p>

    <div class="article <?php echo htmlspecialchars($layout); ?>" 
        <?php if ($layout === 'background_image' && !empty($image_path)): ?>
            style="background-image: url('<?php echo $image_path; ?>');"
        <?php endif; ?>
    >
        <!-- Obrazek dla image_float musi być osadzony wewnątrz treści! -->
        <?php if ($layout === 'image_float' && !empty($image_path) && file_exists($image_path)): ?>
            <img src="<?php echo $image_path; ?>" alt="Obrazek do artykułu" class="image-float" />
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

        <!-- Obrazek dla układu bottom-image -->
        <?php if ($layout === 'bottom_image' && !empty($image_path) && file_exists($image_path)): ?>
            <img src="<?php echo $image_path; ?>" alt="Obrazek do artykułu" />
        <?php endif; ?>
    </div>

    <div class="jasniejszydiv">
        <p><a href="index.php">Powrót do listy artykułów</a></p>
    </div>

</body>
</html>
