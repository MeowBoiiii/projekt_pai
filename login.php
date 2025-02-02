<?php
// login.php
require_once 'db.php';

// Jeśli jesteśmy już zalogowani, przekieruj do panelu administratora
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_panel.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Wyszukaj użytkownika w bazie
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Sprawdzenie hasła (załóżmy, że hasło jest zahashowane w bazie)
        if (password_verify($password, $user['password'])) {
            // Ustawienie flagi zalogowania
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $user['username'];

            header('Location: admin_panel.php');
            exit;
        } else {
            $error = "Nieprawidłowe hasło!";
        }
    } else {
        $error = "Nie znaleziono użytkownika!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Logowanie</title>
</head>
<body>
    <h1>Logowanie administratora</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div>
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" name="username" id="username" required />
        </div>
        <div>
            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required />
        </div>
        <button type="submit">Zaloguj</button>
    </form>
</body>
</html>
