<?php
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: articles.php');
    exit;
}

// Pobranie artykułu
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: articles.php');
    exit;
}

// Aktualizacja artykułu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $content = $_POST['content'] ?? '';
    $layout = $_POST['layout'] ?? 'no_image';
    
    $stmt = $pdo->prepare("UPDATE articles SET title = ?, summary = ?, content = ?, layout = ? WHERE id = ?");
    $stmt->execute([$title, $summary, $content, $layout, $id]);

    $_SESSION['message'] = "Artykuł został zaktualizowany!";
    header("Location: articles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj artykuł</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-panel">
        <h1>Edytuj artykuł</h1>
        <form method="post">
            <label>Tytuł:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>

            <label>Streszczenie:</label>
            <textarea name="summary"><?php echo htmlspecialchars($article['summary']); ?></textarea>

            <label>Treść:</label>
            <textarea name="content"><?php echo htmlspecialchars($article['content']); ?></textarea>

            <label>Układ:</label>
            <select name="layout">
                <option value="top_image" <?php if ($article['layout'] === 'top_image') echo 'selected'; ?>>Zdjęcie na górze</option>
                <option value="image_float" <?php if ($article['layout'] === 'image_float') echo 'selected'; ?>>Tekst opływający zdjęcie</option>
                <option value="bottom_image" <?php if ($article['layout'] === 'bottom_image') echo 'selected'; ?>>Zdjęcie na dole</option>
                <option value="background_image" <?php if ($article['layout'] === 'background_image') echo 'selected'; ?>>Zdjęcie jako tło</option>
                <option value="no_image" <?php if ($article['layout'] === 'no_image') echo 'selected'; ?>>Bez zdjęcia</option>
            </select>

            <button type="submit">Zapisz zmiany</button>
        </form>
    </div>
</body>
</html>
