<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Dodajemy sesję, aby korzystać z $_SESSION

require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $content = $_POST['content'] ?? '';
    $layout = $_POST['layout'] ?? 'no_image';
    
    $image_path = null; // Domyślnie brak zdjęcia
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $image_path = $target_path;
        } else {
            $message = "Błąd podczas przesyłania zdjęcia.";
        }
    }

    // Jeśli brak zdjęcia, można ustawić domyślną wartość lub NULL
    if ($image_path === null) {
        $image_path = 'uploads/no_image.jpg'; // Przykład z domyślnym zdjęciem
    }

    $stmt = $pdo->prepare("INSERT INTO articles (title, summary, content, layout, image_path) 
                           VALUES (:title, :summary, :content, :layout, :image_path)");
    $stmt->execute([
        'title' => $title,
        'summary' => $summary,
        'content' => $content,
        'layout' => $layout,
        'image_path' => $image_path
    ]);

    $_SESSION['message'] = "Artykuł został dodany pomyślnie!";
    header('Location: admin_panel.php');
    exit;
}

// Pobieranie listy artykułów do edycji/usuwania
$stmt = $pdo->query("SELECT id, title, created_at FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Panel administratora</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="admin-panel">
        <h1>Panel administratora</h1>
        <p>Zalogowany jako: <?php echo $_SESSION['username']; ?></p>
        <p><a href="logout.php">Wyloguj</a></p>

        <?php if (isset($_SESSION['message'])): ?>
            <p class="success"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h2>Dodaj nowy artykuł</h2>

        <form method="post" action="admin_panel.php" enctype="multipart/form-data">
            <div>
                <label for="title">Tytuł:</label><br/>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="summary">Streszczenie:</label><br/>
                <textarea name="summary" id="summary" rows="3"></textarea>
            </div>
            <div>
                <label for="layout">Wybierz układ artykułu:</label><br/>
                <select name="layout" id="layout">
                    <option value="top_image">Zdjęcie na górze</option>
                    <option value="image_float">Tekst opływający zdjęcie</option>
                    <option value="bottom_image">Zdjęcie na dole</option>
                    <option value="background_image">Zdjęcie jako tło</option>
                    <option value="no_image">Bez zdjęcia</option>
                </select>
            </div>
            <div>
                <label for="image">Zdjęcie (opcjonalnie):</label><br/>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <div>
                <label for="content">Treść artykułu:</label><br/>
                <textarea name="content" id="content" rows="8"></textarea>
            </div>
            <button type="submit">Dodaj artykuł</button>
        </form>

        <h2>Lista artykułów</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Tytuł</th>
                <th>Data</th>
                <th>Akcje</th>
            </tr>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                    <td><?php echo $article['created_at']; ?></td>
                    <td>
                        <a href="edit_article.php?id=<?php echo $article['id']; ?>">Edytuj</a> |
                        <a href="delete_article.php?id=<?php echo $article['id']; ?>" onclick="return confirm('Na pewno chcesz usunąć?');">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
