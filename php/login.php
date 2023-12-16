<?php
include 'db-polaczenie.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_kli = $_POST['login']; // Get login from form
    $haslo_kli = $_POST['password']; // Get password from form

    // Prepare a select statement
    $sql = "SELECT Nr_klienta FROM klient WHERE login_kli = ? AND haslo_kli = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_kli, $haslo_kli);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        // Here, you can start a session and store user info if needed
        //echo "Login successful";
    session_start();
        // Pobierz Nr_klienta na podstawie loginu
$sql_nr_klienta = "SELECT Nr_klienta FROM klient WHERE login_kli = ?";
$stmt_nr_klienta = $conn->prepare($sql_nr_klienta);
$stmt_nr_klienta->bind_param("s", $login_kli);
$stmt_nr_klienta->execute();
$result_nr_klienta = $stmt_nr_klienta->get_result();

if ($result_nr_klienta->num_rows > 0) {
    $row_nr_klienta = $result_nr_klienta->fetch_assoc();
    $_SESSION['user'] = $row_nr_klienta['Nr_klienta'];
} else {
    echo "Błąd: Brak danych dla klienta o loginie: " . $login_kli;
    exit();
}

// Zamknij połączenie
$stmt_nr_klienta->close();

    header("Location: user.php"); // Redirect to user.php
    } else {
        // Login failed
        echo "Invalid login credentials";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/login.css" />
</head>

<body>
    <header>
        <a href="../php/login.php" class="login-link">Powrót</a>
        <a href="../php/admin.php" class="admin-link">Logowania dla pracownika</a>
    </header>
    <form action="../php/login.php" method="post">
        Login: <input type="text" name="login"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
