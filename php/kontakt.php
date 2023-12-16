<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafePlace - Kontakt</title>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/kontakt.css">
</head>
<body>

    <!-- Logo firmy -->
    <div class="logo-container">
        <img src="../images/logo.png" alt="Logo SafePlace">
    </div>

    <!-- Nawigacja -->
    <nav>
        <ul>
            <li><a href="../index.html">Strona główna</a></li>
            <li><a href="../html/oferta.html">Oferta</a></li>
            <li><a href="../html/aktualnosci.html">Aktualności</a></li>
            <li><a href="../php/kontakt.php">Kontakt</a></li>
        </ul>
    </nav>

    <!-- Przyciski logowania/rejestracji -->
    <header>
        <a href="../php/reg.php" class="reg-link">Załóż konto</a>
        <a href="../php/login.php" class="login-link">Zaloguj się</a>
    </header>

    <!-- Główna zawartość -->
    <main>
        <div class="contact-container">
            <div class="contact-info-map">
                <div class="contact-info-form">
                    <h2>Kontakt z nami</h2>
                    <p>Jeśli masz jakiekolwiek pytania lub potrzebujesz wsparcia, skontaktuj się z nami:</p>
                    
                    <div class="contact-info">
                        <p>Email: kontakt@safeplace.pl</p>
                        <p>Telefon: +48 123 456 789</p>
                    </div>

                    <div class="contact-form">
                        <h3>Formularz kontaktowy</h3>
                        <!-- Tutaj zmieniono atrybut action na skrypt PHP -->
                        <form action="kontakt.php" method="post">
                            <input type="text" name="name" placeholder="Imię i nazwisko" required>
                            <input type="email" name="email" placeholder="Email" required>
                            <textarea name="message" placeholder="Twoja wiadomość" required></textarea>
                            <button type="submit">Wyślij</button>
                        </form>
                    </div>
                </div>

                <div class="map-container right-map">
                    <h3>Znajdź naszą placówkę</h3>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2549.1808465992463!2d18.675399376547727!3d50.28855197156173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4711310230b29c0f%3A0xeab62045ee48e692!2sWydzia%C5%82%20Automatyki%2C%20Elektroniki%20i%20Informatyki%20Politechniki%20%C5%9Al%C4%85skiej!5e0!3m2!1spl!2spl!4v1699202395227!5m2!1spl!2spl" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </div>
    </main>

    <!-- Stopka -->
    <footer>
        <p>SafePlace Sp. z o.o.</p>
        <p>Główna siedziba: ul. Bezpieczna 123, 00-999 Warszawa</p>
        <p>Email: kontakt@safeplace.pl | Tel: +48 123 456 789</p>
    </footer>

</body>
</html>
