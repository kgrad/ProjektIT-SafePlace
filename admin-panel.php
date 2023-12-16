<?php

include 'db-polaczenie.php';


$nr_zespolu = 0;
$id_wydzialu = 0;
$nr_pracownika = 1;
$sql_nr_zespolu = "SELECT NR_zesp FROM pracownik WHERE NR_prac = $nr_pracownika";
$result_nr_zespolu = $conn->query($sql_nr_zespolu);

if ($result_nr_zespolu) {
    // Pobierz wynik
    $row_nr_zespolu = $result_nr_zespolu->fetch_assoc();

    // Teraz użyj pobranej wartości w następnym zapytaniu
    $nr_zespolu = $row_nr_zespolu['NR_zesp'];
    //echo''.$nr_zespolu.'';
    $sql_info_zespolu = "SELECT ID_wydzialu FROM zespoly WHERE Nr_zespolu = $nr_zespolu";
    $result_info_zespolu = $conn->query($sql_info_zespolu);

    // Reszta twojego kodu...
} else {
    echo "Błąd w pierwszym zapytaniu.";
}

// Pobranie ID wydziału 
$sql_info_zespolu = "SELECT ID_wydzialu FROM zespoly WHERE Nr_zespolu = $nr_zespolu";
$result_info_zespolu = $conn->query($sql_info_zespolu);




    $row_info_zespolu = $result_info_zespolu->fetch_assoc();
    $id_wydzialu = $row_info_zespolu["ID_wydzialu"];

    // Pobranie informacji o sejfach klienta z danego wydziału
   $sql_sejfy_klienta = "SELECT * FROM sejf WHERE Nr_klienta IN (SELECT Nr_klienta FROM klient WHERE Nr_wydzialu = $id_wydzialu)";
    $result_sejfy_klienta = $conn->query($sql_sejfy_klienta);

/////////////////////////////////////////////////////////////////////////
    $klient = "SELECT * FROM klient k WHERE k.Nr_wydzialu = $id_wydzialu";
    $result_klient = $conn->query($klient);




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safeplace Admin Dashboard</title>
    <link rel="stylesheet" href="admin-panel.css">
    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <style>
        /* Add style for hiding categories at the start */
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    
    <header>
        <div class="logo">SafePlace</div>
        <div class="user-info">
            <img src="user-avatar.jpg" alt="User Avatar">
            <span>Pracownik</span>
            <div>
                <form method="post" action="logout.php" style="display: inline;">
                    <button type="submit" name="logout" class="logout-asd">Wyloguj</button>
                </form>
            </div>
        </div>
    </header>

    <main>
        <section>
            <h3>Lista Sejfów</h3>
            <?php
            include 'db-polaczenie.php';
            if ($result_sejfy_klienta->num_rows > 0) {
                while ($row_sejf = $result_sejfy_klienta->fetch_assoc()) {
                    echo '<div class="tile">';
                    echo '<h3>ID Sejfu: ' . $row_sejf["ID_sejfu"] . '</h3>';
                    echo '<p>Stan konta: ' . $row_sejf["Stan_konta"] . '</p>';
                    echo '<p>Nazwa sejfu: ' . $row_sejf["Nazwa_sejfu"] . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>Brak danych o sejfach klienta z tego wydziału.</p>";
            }
            ?>
        </section>

        <section>
            <h3>Lista Klientów</h3>
            <?php while ($row_klient = $result_klient->fetch_assoc()): ?>
                <div class="tile">
                    <h3>Imię: <?php echo $row_klient["Imie_kli"]; ?></h3>
                    <h3>Nazwisko: <?php echo $row_klient["Nazw_kli"]; ?></h3>
                    <h3>Numer klienta: <?php echo $row_klient["Nr_klienta"]; ?></h3>
                </div>
            <?php endwhile; ?>
        </section>
    </main>

    <?php $conn->close(); ?>

</body>
</html>
