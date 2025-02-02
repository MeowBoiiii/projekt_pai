<?php
// logout.php
require_once 'db.php';

// Usuwamy dane z sesji
session_unset();
session_destroy();

// Przekierowanie na stronę logowania
header('Location: login.php');
exit;