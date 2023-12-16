<?php
include 'db-polaczenie.php'; // Include the database connection file

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$nr_klienta = $_SESSION['user'];

// Pobierz dane klienta
$sql_klient = "SELECT * FROM klient WHERE Nr_klienta = ?";
$stmt_klient = $conn->prepare($sql_klient);
$stmt_klient->bind_param("s", $nr_klienta);
$stmt_klient->execute();
$result_klient = $stmt_klient->get_result();
$informacje_klienta = $result_klient->fetch_assoc();

// Pobierz sejfy dla danego klienta
$sql_sejf = "SELECT ID_sejfu, Nazwa_sejfu, Stan_konta FROM sejf WHERE Nr_klienta = ?";
$stmt_sejf = $conn->prepare($sql_sejf);
$stmt_sejf->bind_param("s", $nr_klienta);
$stmt_sejf->execute();
$result_sejf = $stmt_sejf->get_result();

// Pobierz transakcje dla danego klienta
$sql_transakcje = "SELECT Data_transakcji, Kwota FROM transakcje WHERE id_sejfu IN (SELECT ID_sejfu FROM sejf WHERE Nr_klienta = ?)";
$stmt_transakcje = $conn->prepare($sql_transakcje);
$stmt_transakcje->bind_param("s", $nr_klienta);
$stmt_transakcje->execute();
$result_transakcje = $stmt_transakcje->get_result();

// Zamknij statementy
$stmt_klient->close();
$stmt_sejf->close();
$stmt_transakcje->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safeplace Dashboard</title>
    <link rel="stylesheet" href="../styles/user.css">
    <!-- Font Awesome Cdn Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
</head>
<body>
    
    <header>
        <div class="logo">SafePlace</div>
        <div class="user-info">
            <img src="../images/user-avatar.jpg" alt="User Avatar">
            <span>Welcome, <?php echo $informacje_klienta['Imie_kli']; ?></span>
            <div>
                <form method="post" action="logout.php" style="display: inline;">
                    <button type="submit" name="logout" class="logout-asd">Wyloguj</button>
                </form>
            </div>
            <a href="../php/ustawienia.php"><i class="fas fa-cogs"></i> Ustawienia</a>
        
        </div>
        
    </header>

    

    <main>
        <section class="overview">
            <div class="recent-transactions">
                <h3>Transakcje</h3>
                <ul>
                    <?php while ($transakcja = $result_transakcje->fetch_assoc()): ?>
                <div class="tile">
                    <p class="transaction-type deposit">Data transakcji: <?php echo $transakcja['Data_transakcji']; ?></p>
                    <p class="transaction-type deposit">Kwota: <?php echo $transakcja['Kwota']; ?> PLN</p>
                </div>
            <?php endwhile; ?>
                </ul>
            </div>
        </section>

        <section class="quick-actions">
            <h2>Salda</h2>
            <div class="tile-container">
                <?php while ($sejf = $result_sejf->fetch_assoc()): ?>
                    <div class="tile">
                        <li class="transaction-type deposit"><span>Sejf:</span> <?php echo $sejf['Nazwa_sejfu']; ?></li>
                        <li class="transaction-type deposit"><span>Stan stan:</span> <?php echo $sejf['Stan_konta']; ?> PLN</li>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        

    </main>


</body>
</html>
