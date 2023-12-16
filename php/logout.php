<?php
// logout.php

// Inicjalizacja sesji
session_start();

// Zakończ sesję
session_destroy();

// Przekieruj użytkownika na stronę logowania (możesz dostosować tę ścieżkę)
header("Location: login.php");
exit();
?>
