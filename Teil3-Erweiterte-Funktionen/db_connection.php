<?php
// Datenbankkonfiguration
$servername = "localhost";
$username = "root";
$password = "";
$database = "Appointment";

try {
    // PDO-Verbindung erstellen
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // PDO-Error-Modus auf Ausnahme setzen
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Fehler für Debugging-Zwecke protokollieren
    error_log("Verbindung fehlgeschlagen: " . $e->getMessage());

    // Eine benutzerfreundliche Nachricht anzeigen
    die("Hoppla! Etwas ist schiefgegangen. Bitte versuchen Sie es später erneut.");
}
?>
