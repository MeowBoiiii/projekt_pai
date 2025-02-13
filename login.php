<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();  // Zainicjuj sesję

// Załaduj połączenie z bazą danych
require_once 'db.php';

// Jeśli użytkownik jest już zalogowany, przekieruj go do odpowiedniego panelu
if (isset($_SESSION['user_id'])) {
    // Sprawdź rolę i przekieruj do odpowiedniego panelu
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_panel.php'); // Jeśli to admin, idź do admin_panel.php
    } else {
        header('Location: index.php'); // Zwykły użytkownik na stronę główną
    }
    exit;
}

// Jeśli formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Pobierz użytkownika z bazy
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Logowanie udane - zapisujemy dane w sesji
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Przekierowanie w zależności od roli
        if ($_SESSION['role'] === 'admin') {
            header('Location: admin_panel.php'); // Przekierowanie dla admina
        } else {
            header('Location: index.php'); // Przekierowanie dla zwykłego użytkownika
        }
        exit;
    } else {
        $error = "Nieprawidłowy login lub hasło!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Logowanie</title>
</head>
<body>
    <h1>Logowanie</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div>
            <label for="username">Nazwa użytkownika:</label><br />
            <input type="text" name="username" id="username" required /><br />
        </div>
        <div>
            <label for="password">Hasło:</label><br />
            <input type="password" name="password" id="password" required /><br />
        </div>
        <button type="submit">Zaloguj</button>
    </form>

    <h2>Dostępne konta testowe:</h2>
    <p><strong>Administrator:</strong> Login: admin | Hasło: admin123</p>
    <p><strong>Użytkownik:</strong> Login: user | Hasło: user123</p>
</body>
</html>
