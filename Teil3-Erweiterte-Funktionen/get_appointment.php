<?php
// Datenbankverbindung einbinden
include 'db_connection.php';

// Termindetails anhand der ID abrufen
if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];

        // SQL-Anweisung vorbereiten
        $sql = "SELECT a.*, c.* ,a.id FROM appointments a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = :id";
        $stmt = $pdo->prepare($sql);

        // Parameter binden und Abfrage ausführen
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Termindetails abrufen
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        // Überprüfen, ob Termin existiert
        if ($appointment) {
            // Termindetails als JSON zurückgeben
            echo json_encode($appointment);
        } else {
            // Fehlermeldung zurückgeben, wenn Termin nicht gefunden wurde
            echo json_encode(array('error' => 'Termin nicht gefunden'));
        }
    } catch (PDOException $e) {
        // Fehlermeldung zurückgeben, wenn eine Ausnahme auftritt
        echo json_encode(array('error' => 'Datenbankfehler: ' . $e->getMessage()));
    }
} else {
    // Fehlermeldung zurückgeben, wenn keine ID-Parameter übergeben wurde
    echo json_encode(array('error' => 'ID-Parameter nicht übergeben'));
}
?>
