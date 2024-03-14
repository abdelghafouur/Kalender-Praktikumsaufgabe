<?php
// Datenbankverbindung einbinden
include 'db_connection.php';

// Überprüfen, ob Termin-ID bereitgestellt wird
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // SQL-Anweisung vorbereiten und Termin löschen
    $sql = "DELETE FROM appointments WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    // Löschen des Termins ausführen
    if ($stmt->execute()) {
        // Erfolgsmeldung zurückgeben
        echo 'success';
    } else {
        // Fehlermeldung zurückgeben, wenn das Löschen fehlschlägt
        echo 'error_deletion_failed';
    }
} else {
    // Benutzer darüber informieren, dass die Termin-ID fehlt oder ungültig ist
    echo 'error_invalid_id';
}
?>
