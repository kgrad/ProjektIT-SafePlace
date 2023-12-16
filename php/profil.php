<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db-polaczenie.php';

$nr_klienta = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy formularz edycji danych klienta został przesłany
    if (isset($_POST['edit_profile'])) {
        $nowe_imie = $_POST['nowe_imie'];
        $nowe_nazwisko = $_POST['nowe_nazwisko'];
        $nowy_login = $_POST['nowy_login'];
        $nowy_email = $_POST['nowy_email'];
        $nowy_numer_tel = $_POST['nowy_numer_tel'];

        // Aktualizuj dane klienta w bazie danych
        $sql_update_profile = "UPDATE klient SET Imie_kli = ?, Nazw_kli = ?, login_kli = ?, mail_kli = ?, nr_tel_kli = ? WHERE Nr_klienta = ?";
        $stmt_update_profile = $conn->prepare($sql_update_profile);
        $stmt_update_profile->bind_param("ssssss", $nowe_imie, $nowe_nazwisko, $nowy_login, $nowy_email, $nowy_numer_tel, $nr_klienta);
        $stmt_update_profile->execute();

        // Zamknij połączenie
        $stmt_update_profile->close();
    }

    // Sprawdź, czy formularz zmiany hasła został przesłany
    if (isset($_POST['change_password'])) {
        $obecne_haslo = $_POST['obecne_haslo'];
        $nowe_haslo1 = $_POST['nowe_haslo1'];
        $nowe_haslo2 = $_POST['nowe_haslo2'];

        // Sprawdź poprawność obecnego hasła
        $sql_check_password = "SELECT haslo_kli FROM klient WHERE Nr_klienta = ?";
        $stmt_check_password = $conn->prepare($sql_check_password);
        $stmt_check_password->bind_param("s", $nr_klienta);
        $stmt_check_password->execute();
        $result_check_password = $stmt_check_password->get_result();

        // Jeśli hasło jest poprawne, zaktualizuj je w bazie danych
        if ($result_check_password->num_rows > 0) {
            $row_check_password = $result_check_password->fetch_assoc();

            if ($obecne_haslo === $row_check_password['haslo_kli'] && $nowe_haslo1 === $nowe_haslo2) {
                $sql_update_password = "UPDATE klient SET haslo_kli = ? WHERE Nr_klienta = ?";
                $stmt_update_password = $conn->prepare($sql_update_password);
                $stmt_update_password->bind_param("ss", $nowe_haslo1, $nr_klienta);
                $stmt_update_password->execute();

                // Zamknij połączenie
                $stmt_update_password->close();
            } else {
                // Wyświetl błąd, jeśli obecne hasło jest nieprawidłowe lub nowe hasła nie pasują
                echo "Incorrect current password or new passwords do not match.";
            }
        } else {
            // Wyświetl błąd, jeśli nie udało się pobrać hasła użytkownika
            echo "Error fetching user password.";
        }

        // Zamknij połączenie
        $stmt_check_password->close();
    }
}

// Pobierz dane klienta na podstawie Nr_klienta
$sql = "SELECT * FROM klient WHERE Nr_klienta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nr_klienta);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/profil.css" id="theme-style">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<button class="back-button" onclick="window.location.href='user.php'">Powrót</button>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Profil Klienta
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">Konto</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Hasło</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Posiadane sejfy</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-connections">Wiadomości</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body">
                                <form method="post" action="profil.php">
                                    <div class="form-group">
                                        <label for="nowe_imie" class="form-label">Imię</label>
                                        <input type="text" name="nowe_imie" class="form-control" id="nowe_imie" value="<?php echo $row['Imie_kli']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nowe_nazwisko" class="form-label">Nazwisko</label>
                                        <input type="text" name="nowe_nazwisko" class="form-control" id="nowe_nazwisko" value="<?php echo $row['Nazw_kli']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nowy_login" class="form-label">Login</label>
                                        <input type="text" name="nowy_login" class="form-control" id="nowy_login" value="<?php echo $row['login_kli']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nowy_email" class="form-label">Email</label>
                                        <input type="text" name="nowy_email" class="form-control" id="nowy_email" value="<?php echo $row['mail_kli']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nowy_numer_tel" class="form-label">Numer telefonu</label>
                                        <input type="text" name="nowy_numer_tel" class="form-control" id="nowy_numer_tel" value="<?php echo $row['nr_tel_kli']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="edit_profile" class="btn btn-primary" value="zapisz">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <form method="post" action="profil.php">
                                <div class="form-group">
                                    <label for="obecne_haslo" class="form-label">Obecne hasło</label>
                                    <input type="password" name="obecne_haslo" class="form-control" id="obecne_haslo" value="" >
                                </div>
                                <div class="form-group">
                                    <label for="nowe_haslo1" class="form-label">Nowe hasło</label>
                                    <input type="password" name="nowe_haslo1" class="form-control" id="nowe_haslo1" value="">
                                </div>
                                <div class="form-group">
                                    <label for="nowe_haslo2" class="form-label">Wpisz ponownie nowe hasło</label>
                                    <input type="password" name="nowe_haslo2" class="form-control" id="nowe_haslo2" value="">
                                </div>
                                <input type="submit" name="change_password" class="btn btn-primary" value="zapisz">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-info">
                           
                        </div>
                        <div class="tab-pane fade" id="account-social-links">
                           
                        </div>
                        <div class="tab-pane fade" id="account-connections">
    
                        </div>
                        <div class="tab-pane fade" id="account-notifications">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
</body>

</html>