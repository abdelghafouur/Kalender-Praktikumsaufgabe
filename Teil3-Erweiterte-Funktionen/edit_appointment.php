<?php
// Verbindung zur Datenbank herstellen
include 'db_connection.php';

// Überprüfen, ob Formulardaten übermittelt wurden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formulardaten abrufen und säubern
    $title = htmlspecialchars($_POST['editTitle']);
    $description = htmlspecialchars($_POST['editDescription']);
    $date = htmlspecialchars($_POST['editDate']);
    // Sicherstellen, dass end_date ordnungsgemäß behandelt wird
    $end_date = (!empty($_POST['end_date'])) ? htmlspecialchars($_POST['end_date']) : null;
    $is_full_day = htmlspecialchars($_POST['is_full_dayEdite']);
    // Sicherstellen, dass start_time und end_time ordnungsgemäß behandelt werden
    if ($is_full_day == 0) {
        $start_time = htmlspecialchars($_POST['editStartTime']);
        $end_time = htmlspecialchars($_POST['editEndTime']);
    } else {
        $start_time = null;
        $end_time = null;
    }
    // Kategorie-ID säubern
    $category_id = htmlspecialchars($_POST['category_id']);

    // SQL-Anweisung vorbereiten und ausführen, um den Termin zu aktualisieren
    $sql = "UPDATE appointments SET title = :title, description = :description, date = :date, end_date = :end_date, start_time = :start_time, is_full_day = :is_full_day, end_time = :end_time, category_id = :category_id WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    // Parameter binden
    $stmt->bindParam(':id', $_POST['editId'], PDO::PARAM_INT);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':is_full_day', $is_full_day);
    $stmt->bindParam(':category_id', $category_id);

    // SQL-Anweisung ausführen
    if ($stmt->execute()) {
        // Zur Indexseite mit Erfolgsmeldung umleiten
        header("Location: index.php?msgEdite=success");
        exit();
    } else {
        // Zur Indexseite mit Fehlermeldung umleiten
        header("Location: index.php?msgEdite=error");
        exit();
    }
} else {
    // Fehlermeldung zurückgeben, wenn keine Daten übermittelt wurden
    header("Location: index.php?msgEdite=error");
}
?>
