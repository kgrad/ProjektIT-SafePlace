<?php
include 'db-polaczenie.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $login_kli = $_POST['login'];
    $haslo_kli = $_POST['haslo']; 
    $Imie_kli  = $_POST['imie'];
    $Nazw_kli  = $_POST['nazwisko'];
    $mail_kli  = $_POST['mail'];
    $Pesel_kli = $_POST['pesel'];

    // Prepare a statement
    $sql = "INSERT INTO klient (Imie_kli, Nazw_kli, Pesel_kli, login_kli, haslo_kli, mail_kli) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $Imie_kli, $Nazw_kli, $Pesel_kli, $login_kli, $haslo_kli, $mail_kli);

    // Execute the query
    if ($stmt->execute()) {
        echo "Rejestracja udana!";
    } else {
        echo "Błąd rejestracji: " . $stmt->error;
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="../styles/reg.css" />
</head>

<body>
    <header>
        <a href="../index.html" class="login-link">Powrót</a>
    </header>
    <form autocomplete="off" action="reg.php" method="post">
   Imię: <input type="text" name="imie" ><br>
   Nazwisko: <input type="text" name="nazwisko" ><br>
   Pesel: <input type="text" name="pesel" ><br>
   Login: <input type="text" name="login" ><br>
   Hasło: <input type="password" name="haslo" ><br>
   Email: <input type="email" name="mail" ><br>
   <input type="submit" value="Zarejestruj">
    </form>

    
</body>
</html>
	