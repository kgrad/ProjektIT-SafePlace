<?php
include 'db-polaczenie.php'; // Make sure this file correctly sets up the connection to your database.

session_start();

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['pracownik'])) {
    header("Location: admin.php"); // Przekieruj do strony logowania, jeśli nie jest zalogowany
    exit();
}

// Pobierz Nr_prac i NR_zesp z sesji
$nr_pracownika = $_SESSION['pracownik']['Nr_prac'];
$nr_zespolu_pracownika = $_SESSION['pracownik']['NR_zesp'];

// Get the department ID of the employee
$sql = "SELECT ID_wydzialu FROM zespoly WHERE Nr_zespolu = $nr_zespolu_pracownika";
$result = $conn->query($sql);
$id_wydzialu = $result->fetch_assoc()['ID_wydzialu'];

// Get clients in the same department
$sql_klienci = "SELECT * FROM klient WHERE Nr_wydzialu = $id_wydzialu";
$result_klienci = $conn->query($sql_klienci);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sejfu = $_POST['id_sejfu'];
    $kwota = $_POST['kwota'];
    $id_pracownika = $_POST['id_pracownika']; // You can also dynamically fetch this based on logged-in user

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert transaction
        $sql_transakcja = "INSERT INTO transakcje (Kwota, Id_sejfu, Data_transakcji, Id_pracownika) VALUES ('$kwota', '$id_sejfu', NOW(), '$id_pracownika')";
        $conn->query($sql_transakcja);

        // Update balance in sejf
        $sql_update_balance = "UPDATE sejf SET Stan_konta = Stan_konta + $kwota WHERE ID_sejfu = $id_sejfu";
        $conn->query($sql_update_balance);

        // Commit transaction
        $conn->commit();

        echo "Transaction recorded and balance updated successfully";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .tile { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; cursor: pointer; }
        .form-container { display: none; }
    </style>
<link rel="stylesheet" href="../styles/admin-panel.css">
</head>
<body>
<header>
        <div class="logo">SafePlace</div>
        <div class="user-info">
            <img src="../images/admin-avatar.jpg" alt="admin Avatar">
            <span>Pracownik</span>
            <div>
                <form method="post" action="logout.php" style="display: inline;">
                    <button type="submit" name="logout" class="logout-asd">Wyloguj</button>
                </form>
            </div>
        </div>
    </header>
    <h2>Klienci w tym samym wydziale</h2>

    <!-- List of clients -->
    <div id="clients">
        <?php while($row = $result_klienci->fetch_assoc()): ?>
            <div class="tile" onclick="document.getElementById('form-container-<?php echo $row['Nr_klienta']; ?>').style.display='block'">
                <p><?php echo $row['Imie_kli']." ".$row['Nazw_kli']; ?></p>
            </div>
            <!-- Hidden form for each client -->
            <div id="form-container-<?php echo $row['Nr_klienta']; ?>" class="form-container">
                <h3>Transakcje dla klienta: <?php echo $row['Imie_kli']." ".$row['Nazw_kli']; ?></h3>
                <form method="post">
                    <input type="hidden" name="id_pracownika" value="<?php echo $nr_pracownika; ?>">
                    <label for="id_sejfu">Wybierz Sejf:</label>
                    <select name="id_sejfu" required>
                        <?php
                        $nr_klienta = $row['Nr_klienta'];
                        $sql_sejfy = "SELECT * FROM sejf WHERE Nr_klienta = $nr_klienta";
                        $result_sejfy = $conn->query($sql_sejfy);
                        while ($sejf = $result_sejfy->fetch_assoc()) {
                            echo "<option value='".$sejf['ID_sejfu']."'>".$sejf['ID_sejfu']."</option>";
                        }
                        ?>
                    </select><br>
                    <label for="kwota">Kwota:</label>
                    <input type="number" name="kwota" required><br>
                    <button type="submit">Wyślij</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function hideForms() {
            var forms = document.getElementsByClassName('form-container');
            for(var i = 0; i < forms.length; i++) {
                forms[i].style.display = 'none';
            }
        }
        window.onload = hideForms;
    </script>

</body>
</html>	