<?php
include 'db-polaczenie.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_prac = $_POST['login']; // Get login from form
    $haslo_prac = $_POST['password']; // Get password from form

    // Prepare a select statement
    $sql = "SELECT NR_prac FROM pracownik WHERE login_prac = ? AND haslo_prac = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_prac, $haslo_prac);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        // Here, you can start a session and store user info if needed
        session_start();

        // Pobierz Nr_prac na podstawie loginu
        $sql_nr_prac = "SELECT NR_prac FROM pracownik WHERE login_prac = ?";
        $stmt_nr_prac = $conn->prepare($sql_nr_prac);
        $stmt_nr_prac->bind_param("s", $login_prac);
        $stmt_nr_prac->execute();
        $result_nr_prac = $stmt_nr_prac->get_result();

        if ($result_nr_prac->num_rows > 0) {
            $row_nr_prac = $result_nr_prac->fetch_assoc();
            $_SESSION['admin'] = $row_nr_prac['NR_prac'];
        } else {
            echo "Błąd: Brak danych dla pracownika o loginie: " . $login_prac;
            exit();
        }

        // Zamknij połączenie
        $stmt_nr_prac->close();
        header("Location: admin-panel.php"); // Redirect to admin-panel.php
        exit(); // Dodaj exit(), aby zakończyć wykonywanie skryptu po przekierowaniu
    } else {
        // Login failed
        echo "Invalid login credentials";
    }

    // Zamknij połączenie
    $stmt->close();
    $conn->close(); // Dodaj zamknięcie połączenia
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css" />
</head>

<body>
    <header>
        <a href="login.php" class="login-link">Powrót</a>
    </header>
    <form action="admin.php" method="post">
        Login: <input type="text" name="login"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>	