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
        $_SESSION['user'] = $login_kli; // Store user login in session
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
    <link rel="stylesheet" href="login.css" />
</head>

<body>
    <header>
        <a href="index.html" class="login-link">Powrót</a>
    </header>
    <form action="login.php" method="post">
        Login: <input type="text" name="login"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    
</body>


</html>	
