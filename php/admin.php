<?php
include 'db-polaczenie.php'; // Dołącz plik z połączeniem do bazy danych

// Sprawdź, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_prac = $_POST['login']; // Pobierz login z formularza
    $haslo_prac = $_POST['password']; // Pobierz hasło z formularza

    // Przygotuj zapytanie SELECT
    $sql = "SELECT * FROM pracownik WHERE login_prac = ? AND haslo_prac = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_prac, $haslo_prac);

    // Wykonaj zapytanie
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Logowanie udane
        session_start();

        // Pobierz wszystkie dane pracownika na podstawie loginu
        $_SESSION['pracownik'] = $result->fetch_assoc();

        // Zamknij połączenie
        $stmt->close();
        $conn->close(); // Zamknij połączenie z bazą danych

        header("Location: admin-panel.php"); // Przekieruj do admin-panel.php
        exit(); // Dodaj exit(), aby zakończyć wykonywanie skryptu po przekierowaniu
    } else {
        // Logowanie nieudane
        echo "Nieprawidłowe dane logowania";
    }

    // Zamknij połączenie
    $stmt->close();
    $conn->close(); // Zamknij połączenie z bazą danych
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/login.css" />
</head>

<body>
    <header>
        <a href="../php/login.php" class="login-link">Powrót</a>
    </header>
    <form action="../php/admin.php" method="post">
        Login: <input type="text" name="login"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>	
