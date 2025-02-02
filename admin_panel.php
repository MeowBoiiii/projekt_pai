<?php
// admin_panel.php
require_once 'db.php';

// Sprawdź, czy użytkownik jest zalogowany jako administrator
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = "";

// Obsługa przesłanego formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $content = $_POST['content'] ?? '';
    $layout = $_POST['layout'] ?? 'no_image';
    
    // Obsługa uploadu pliku (opcjonalnie)
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $upload_dir = 'uploads/';
        // Upewnij się, że katalog uploads istnieje i ma prawa zapisu
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $image_path = $target_path;
        }
    }

    // Zapis do bazy danych
    $stmt = $pdo->prepare("INSERT INTO articles (title, summary, content, layout, image_path) 
                           VALUES (:title, :summary, :content, :layout, :image_path)");
    $stmt->execute([
        'title' => $title,
        'summary' => $summary,
        'content' => $content,
        'layout' => $layout,
        'image_path' => $image_path
    ]);

    $message = "Artykuł został dodany pomyślnie!";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Panel administratora</title>
</head>
<body>
    <h1>Panel administratora</h1>
    <p>Zalogowany jako: <?php echo $_SESSION['username']; ?></p>
    <p><a href="logout.php">Wyloguj</a></p>

    <h2>Dodaj nowy artykuł</h2>
    <?php if (!empty($message)): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

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
</body>
</html>
