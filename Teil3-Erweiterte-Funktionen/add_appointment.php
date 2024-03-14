<?php
// Verbindung zur Datenbank herstellen
include 'db_connection.php';

// Überprüfen, ob Formulardaten übermittelt wurden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formulardaten abrufen
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $is_full_day = $_POST['is_full_day'];
    $start_time = ($is_full_day == 0) ? $_POST['start_time'] : null;
    $end_time = ($is_full_day == 0) ? $_POST['end_time'] : null;
    $category_id = $_POST['category'];

    // Überprüfen, ob es Ereignisse für den gesamten Tag gibt
    $event_spans_entire_day = false;
    $sql_check_day = "SELECT * FROM appointments WHERE date = :date AND is_full_day = 1";
    $stmt_check_day = $pdo->prepare($sql_check_day);
    $stmt_check_day->bindParam(':date', $date);
    $stmt_check_day->execute();
    if ($stmt_check_day->rowCount() > 0) {
        $event_spans_entire_day = true;
    }

    // Überprüfen, ob es Ereignisse für die gesamte Dauer des Zeitbereichs gibt
    $event_spans_time_range = false;
    if (!$event_spans_entire_day && $is_full_day == 0) {
        $sql_check_time = "SELECT * FROM appointments WHERE date = :date AND (
            (start_time < :start_time AND end_time > :start_time) OR  
            (start_time < :end_time AND end_time > :end_time) OR     
            (start_time >= :start_time AND end_time <= :end_time) OR  
            (start_time <= :start_time AND end_time >= :end_time)    
        )";        
        $stmt_check_time = $pdo->prepare($sql_check_time);
        $stmt_check_time->bindParam(':date', $date);
        $stmt_check_time->bindParam(':start_time', $start_time);
        $stmt_check_time->bindParam(':end_time', $end_time);
        $stmt_check_time->execute();
        if ($stmt_check_time->rowCount() > 0) {
            $event_spans_time_range = true;
        }
    }

    // Wenn kein Ereignis den gesamten Tag oder den gesamten Zeitbereich abdeckt, den Termin hinzufügen
    if (!$event_spans_entire_day && !$event_spans_time_range) {
        // SQL-Anweisung vorbereiten und ausführen, um den Termin hinzuzufügen
        $sql = "INSERT INTO appointments (title, description, date, end_date, start_time, end_time, is_full_day, category_id)
                VALUES (:title, :description, :date, :end_date, :start_time, :end_time, :is_full_day, :category_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':is_full_day', $is_full_day);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            // Erfolgsmeldung zurückgeben
            echo 'success';
        } else {
            // Fehlermeldung zurückgeben
            echo 'error';
        }
    } else {
        // Geeignete Fehlermeldung zurückgeben
        if ($event_spans_entire_day) {
            echo 'error_day';
        } else {
            echo 'error_time';
        }
    }
} else {
    // Fehlermeldung zurückgeben, wenn keine Daten übermittelt wurden
    echo 'error_no_data';
}

// Datenbankverbindung schließen
$pdo = null;
