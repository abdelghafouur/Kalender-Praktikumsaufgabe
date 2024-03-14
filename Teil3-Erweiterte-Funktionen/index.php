<?php
include 'db_connection.php';

// Datenbankverbindung öffnen und Kategorien abrufen
$sql = "SELECT id, name FROM categories";
$result = $pdo->query($sql);
$categories = $result->fetchAll(PDO::FETCH_ASSOC);

// Standardmäßig auf den aktuellen Monat und das aktuelle Jahr setzen
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$view = isset($_GET['view']) ? $_GET['view'] : 'month';
$week = isset($_GET['week']) ? $_GET['week'] : date('W');
$day = isset($_GET['day']) ? $_GET['day'] : date('j');

// Anzahl der Tage im Monat
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Erster Tag des Monats
$firstDay = mktime(0, 0, 0, $month, 1, $year);

// Monats- und Wochentagsnamen erhalten
$monthName = date('F', $firstDay);
$dayOfWeek = date('N', $firstDay);
$currentWeek = date('W');

// Wochentage ab Montag
$daysOfWeek = ["Mon", "Die", "Mit", "Don", "Fre", "Sam", "Son"];

// Überprüfen, ob der Parameter 'msgEdite' in der URL vorhanden ist
if (isset($_GET['msgEdite'])) {
    // Den Nachrichtentyp abrufen (Erfolg oder Fehler)
    $msg = $_GET['msgEdite'];

    // Die Alert-Nachricht basierend auf dem Nachrichtentyp anzeigen
    if ($msg === 'success') {
        // Erfolgsmeldung anzeigen und weiterleiten
        echo "<div class='alert success'>Termin erfolgreich aktualisiert.</div>";
    } else {
        // Fehlermeldung anzeigen und weiterleiten
        echo "<div class='alert error'>Fehler beim Aktualisieren des Termins.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Kalender</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <!-- Bearbeiten Sie das Termin-Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModalEdite()">&times;</span>
            <h2 style='text-align: center;'>Termin bearbeiten</h2>
            <form id="editForm" action="edit_appointment.php" method="POST" onsubmit="return validateFormEdit()">
                <input type="hidden" name="editId" id="editId">
                <div class="form-row">
                    <label for="editTitle">Titel:</label><br>
                    <input type="text" name="editTitle" id="editTitle">&nbsp;(*)
                </div>
                <div class="form-row">
                    <label for="editDescription">Beschreibung:</label>
                    <textarea name="editDescription" id="editDescription"></textarea>
                </div>
                <div class="form-row">
                    <label for="editDate">Datum:</label>
                    <input type="date" name="editDate" id="editDate">&nbsp;(*)
                </div>
                <div class="form-row">
                    <label for="end_date">Enddatum:</label>
                    <input type="date" name="end_date" id="end_dateEdite">
                </div>
                <div class="form-row">
                    <label>Ganztägiges Ereignis:</label>
                    <input type="radio" name="is_full_dayEdite" class="is_full_day" id="isFullDayYes" value="1"> Ja
                    <input type="radio" name="is_full_dayEdite" class="is_full_day" id="isFullDayNo" value="0"> Nein
                </div>
                <div id="timeInputsEdite">
                    <div class="form-row">
                        <label for="editStartTime">Startzeit:</label>
                        <input type="time" name="editStartTime" id="editStartTime">&nbsp;(*)
                    </div>
                    <div class="form-row">
                        <label for="editEndTime">Endzeit:</label>
                        <input type="time" name="editEndTime" id="editEndTime">&nbsp;(*)
                    </div>
                </div>
                <div class="form-row">
                    <label for="editCategory">Kategorie:</label>
                    <select name="category_id" id="editCategory">
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <label></label>
                    <button type="submit" name="submit">Änderungen speichern</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hinzufügen Termin-Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2 style='text-align: center;'>Termin hinzufügen</h2>
            <form id="addForm">
                <div class="form-row">
                    <label for="title">Titel:</label>
                    <input type="text" name="title" id="title">&nbsp;(*)
                </div>
                <div class="form-row">
                    <label for="description">Beschreibung:</label>
                    <textarea name="description" id="description"></textarea>
                </div>
                <div class="form-row">
                    <label for="date">Datum:</label>
                    <input type="date" name="date" id="date">&nbsp;(*)
                </div>
                <div class="form-row">
                    <label for="end_date">Enddatum:</label>
                    <input type="date" name="end_date" id="end_date">
                </div>
                <div class="form-row">
                    <label>Ganztägiges Ereignis:</label>
                    <input type="radio" name="is_full_day" id="full_day_no" class="is_full_day" value="0" checked> Nein
                    <input type="radio" name="is_full_day" class="is_full_day" value="1"> Ja
                </div>
                <div id="timeInputs">
                    <div class="form-row">
                        <label for="start_time">Startzeit:</label>
                        <input type="time" name="start_time" id="start_time">&nbsp;(*)
                    </div>
                    <div class="form-row">
                        <label for="end_time">Endzeit:</label>
                        <input type="time" name="end_time" id="end_time">&nbsp;(*)
                    </div>
                </div>
                <div class="form-row">
                    <label for="category">Kategorie:</label>
                    <select name="category" id="category">
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <label></label>
                    <button type="submit" onclick="submitAddAppointment(event)">Änderungen speichern</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Anzeigetermin-Modal -->
    <div id="displayModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDisplayEventsModel()">&times;</span>
            <h2 style='text-align: center;'>Termin anzeigen</h2>
            <div class="form-row">
                <label>Titel:</label><br>
                <h4 id="displayTitle"></h4>
            </div>
            <div class="form-row" id="Description_display">
                <label>Beschreibung:</label>
                <h4 id="displayDescription"></h4>
            </div>
            <div class="form-row">
                <label>Datum:</label>
                <h4 id="displayDate"></h4>
            </div>
            <div class="form-row" id="end_datedisplay">
                <label>Enddatum:</label>
                <h4 id="displayend_date"></h4>
            </div>
            <div class="form-row">
                <label>Ganztägiges Ereignis:</label>
                <h4 id="showFullDay"></h4>
            </div>
            <div id="timeInputsdisplay">
                <div class="form-row">
                    <label>Startzeit:</label>
                    <h4 id="displayStartTime"></h4>
                </div>
                <div class="form-row">
                    <label>Endzeit:</label>
                    <h4 id="displayEndTime"></h4>
                </div>
            </div>
            <div class="form-row">
                <label>Kategorie:</label>
                <h4 id="displayCategory"></h4>
            </div>
        </div>
    </div>

    <div id="message" class="message"></div>

    <button onclick="openAddModal()" class="buttun"> <i class='bx bx-calendar-plus bx-tada bx-xs'></i> Termin hinzufügen</button>

    <!-- PHP-Code zum Anzeigen des Kalenders -->
    <div class="calendar-container">

        <div class="navigation">
            <a href='?view=day&month=<?= date('m') ?>&year=<?= date('Y') ?>'> <i class='bx bx-filter'></i> Aktueller Tag </a> &nbsp;
            <a href='?view=week&month=<?= date('m') ?>&year=<?= date('Y') ?>&week=<?= $currentWeek ?>'> <i class='bx bx-filter'></i> Aktuelle Woche</a>&nbsp;
            <a href='?view=month&month=<?= date('m') ?>&year=<?= date('Y') ?>'><i class='bx bx-filter'></i> Aktueller Monat</a>
        </div>

        <?php if ($view == 'month') : ?>

            <!-- Navigation -->
            <div class="navigation">
                <a href='?month=<?= ($month == 1) ? 12 : $month - 1 ?>&year=<?= ($month == 1) ? $year - 1 : $year ?>'>
                    &lt; Vorheriger</a>
                <h3 class='h3'><?= $monthName ?> <?= $year ?></h3>
                <a href='?month=<?= ($month == 12) ? 1 : $month + 1 ?>&year=<?= ($month == 12) ? $year + 1 : $year ?>'>Nächster
                    &gt;</a>
            </div>
            <!-- Kalendertabelle -->
            <table class="tableM">
                <tr>
                    <?php foreach ($daysOfWeek as $day) : ?>
                        <!-- Tagesüberschriften -->
                        <th><?= $day ?></th>
                    <?php endforeach; ?>
                </tr>

                <tr>
                    <?php
                    // Zellen für Tage vor dem ersten Tag des Monats hinzufügen
                    for ($i = 1; $i < $dayOfWeek; $i++) {
                        echo "<td class='other-month'>" . (date('t', mktime(0, 0, 0, $month - 1, 1, $year)) - ($dayOfWeek - $i - 1)) . "</td>";
                    }

                    $dates = [];

                    // Durch jeden Tag des Monats iterieren
                    for ($day = 1; $day <= $daysInMonth; $day++) {

                        // Klasse für den aktuellen Tag festlegen
                        $class = ($day == date('j') && $month == date('n') && $year == date('Y')) ? 'today' : '';
                        echo "<td class='date $class' style='width: 300px'>$day";

                        if (!empty($dates)) {
                            foreach ($dates as $date => $data) {
                                if (date('d', strtotime($date)) == $day) {
                                    foreach ($data as $entry) {
                                        $color = $entry['color'];
                                        $title = (strlen($entry['title']) > 18) ? substr($entry['title'], 0, 18) . '...' : $entry['title'];
                                        $time = $entry['time'];
                                        $id = $entry['id'];
                                        echo "<div class='appointment' style='background-color: $color;'>";
                                        echo "<span class='date Hovericon' onclick='DisplayEventsModel(" . $id . ")'>$title</span><br/>";
                                        echo "<div class='descriptionCurrEv'><span>$time [mehrtägiger Termin]</span>";

                                        echo "</div></div>";
                                    }
                                    unset($dates[$date]);
                                }
                            }
                        }

                        // Termine für diesen Tag aus der Datenbank abrufen
                        $appointments = [];
                        $sql = "SELECT a.*, c.*, a.id FROM appointments a LEFT JOIN categories c ON a.category_id = c.id WHERE YEAR(a.date) = $year AND MONTH(a.date) = $month AND DAY(a.date) = $day ORDER BY a.start_time";
                        $result = $pdo->query($sql);

                        if ($result->rowCount() > 0) {
                            // Termine ausgeben
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                // Überprüfen, ob es sich um ein Ganztagesereignis handelt
                                $time = '';
                                $title = (strlen($row['title']) > 18) ? substr($row['title'], 0, 18) . '...' : $row['title'];
                                if ($row["is_full_day"] == 1) {
                                    // Ganztagesereignis
                                    echo "<div class='appointment' style='background-color: " . $row["color"] . ";'>";
                                    echo "<span class='full-day Hovericon' onclick='DisplayEventsModel(" . $row["id"] . ")'>" . $title . "<br/></span> <div class='containerAll'> <span class='descriptionEv'> Ganztägig</span>";
                                    $time = 'Ganztägig';
                                } else {
                                    // Termin mit Uhrzeit
                                    echo "<div class='appointment' style='background-color: " . $row["color"] . ";'>";
                                    echo "<span class='timed Hovericon' onclick='DisplayEventsModel(" . $row["id"] . ")'>" . $title . " <br/> </span>";
                                    echo "<div class='containerAll'><span class='descriptionEv'>" . substr($row["start_time"], 0, 5);
                                    if (!empty($row["end_time"])) {
                                        // Endzeit anzeigen, wenn sie nicht leer ist
                                        echo " - " . substr($row["end_time"], 0, 5);
                                    }
                                    $time = substr($row["start_time"], 0, 5) . " - " . substr($row["end_time"], 0, 5);
                                    echo "</span>";
                                }

                                // Mehrere Tage überprüfen
                                if (!empty($row["end_date"]) && $row["end_date"] != $row["date"]) {
                                    // Datumszellen von Startdatum bis Enddatum hervorheben
                                    $start = new DateTime($row["date"]);
                                    $end = new DateTime($row["end_date"]);

                                    // Durch jedes Datum im Bereich iterieren
                                    $currentDate = clone $start;
                                    $currentDate = $currentDate->modify('+1 day');
                                    while ($currentDate <= $end) {
                                        $dates[$currentDate->format('Y-m-d')][] = [
                                            'color' => $row["color"], // Beispiel Farbe
                                            'title' => $row["title"], // Beispiel Titel
                                            'id' => $row["id"], // Beispiel ID
                                            'time' => $time // Beispiel Uhrzeit
                                        ];
                                        // Aktuelles Datum um 1 Tag erhöhen
                                        $currentDate->modify('+1 day');
                                    }
                                }

                                // Bearbeitungs- und Löschsymbole hinzufügen
                                echo "<div class='iconsDV'><i class='bx bx-edit bx-sm Hovericon' style='margin-right: 7px;' onclick='openModalEdite(" . $row["id"] . ")'></i>";
                                echo "<i class='bx bx-trash bx-sm Hovericon' onclick='deleteAppointment(" . $row["id"] . ")'></i></div>";
                                echo "</div></div>";
                            }
                        }
                        echo "</td>";

                        // Neue Zeile bei Sonntag beginnen
                        if (date('N', mktime(0, 0, 0, $month, $day, $year)) == 7) {
                            echo "</tr><tr>";
                        }
                    }

                    //  Zellen für Tage nach dem letzten Tag des Monats hinzufügen
                    $lastDayOfWeek = date('N', mktime(0, 0, 0, $month, $daysInMonth, $year));
                    for ($i = $lastDayOfWeek + 1; $i <= 7; $i++) {
                        $nextMonthDay = $i - $lastDayOfWeek;
                        echo "<td class='other-month'>$nextMonthDay</td>";
                    }
                    ?>
                </tr>
            </table>

        <?php elseif ($view == 'week') : ?>

            <!-- Bestimmen des Wochentags des ersten Tags des Jahres -->
            <?php
            $firstDayOfYear = date('N', mktime(0, 0, 0, 1, 1, $year));
            $weekNumber = date('W', strtotime($year . '-01-01 +' . ($week - 1) . ' weeks'));

            // Bestimmen des ersten Tags der Woche
            $firstDayOfWeek = strtotime('+' . (($week - 1) * 7) . ' days', strtotime($year . '-01-01'));
            $firstDayOfWeek = date('M j', $firstDayOfWeek);

            // Bestimmen des letzten Tags der Woche
            $lastDayOfWeek = strtotime('+' . (6 + (($week - 1) * 7)) . ' days', strtotime($year . '-01-01'));
            $lastDayOfWeek = date('M j, Y', $lastDayOfWeek);
            ?>

            <!-- Navigation durch die Wochen -->
            <div class="navigation">
                <a href='?view=week&week=<?= $week - 1 ?>'>
                    < Vorherige Woche </a>&nbsp;&nbsp;
                        <h3> Woche <?= $weekNumber ?> :
                            <?= $firstDayOfWeek . " - " . $lastDayOfWeek ?></h3>
                        &nbsp;&nbsp;<a href='?view=week&week=<?= $week + 1 ?>'> Nächste Woche > </a>
            </div>

            <!-- Kalendertabelle -->
            <table>
                <tr>
                    <th>Uhrzeit</th>
                    <?php foreach ($daysOfWeek as $day) : ?>
                        <th><?= $day ?></th>
                    <?php endforeach; ?>
                </tr>
                <!-- Zeilen für ganztägige Ereignisse -->
                <tr>
                    <td style="font-weight: bold;width: 100px;">Ganztägig</td>
                    <?php for ($i = 1; $i <= 7; $i++) :
                        // Setzen des Datums auf den Anfang der aktuellen Woche
                        $startOfWeek = new DateTime();
                        $startOfWeek->setISODate($year, $week, $i);
                        $currentDate = $startOfWeek->format('Y-m-d');
                        $class = ($currentDate  == sprintf('%04d-%02d-%02d', date('Y'), date('n'), date('j'))) ? 'today' : '';
                    ?>
                        <td style='width: 170px;' class='date <?= $class ?>'>
                            <!-- Abrufen und Anzeigen ganztägiger Ereignisse für diesen Tag -->
                            <?php
                            // SQL-Abfrage, um ganztägige Ereignisse für diesen Tag zu erhalten
                            $sql = "SELECT a.*, c.* ,a.id FROM appointments a LEFT JOIN categories c ON a.category_id = c.id WHERE DATE(a.date) <= :currentDate AND (:currentDate <= DATE(a.end_date) OR a.end_date IS NULL) AND a.is_full_day = 1";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':currentDate', $currentDate);
                            $stmt->execute();
                            $fullDayEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Anzeigen der ganztägigen Ereignisse
                            foreach ($fullDayEvents as $fullDayEvent) {
                                $title = (strlen($fullDayEvent['title']) > 18) ? substr($fullDayEvent['title'], 0, 18) . '...' : $fullDayEvent['title'];
                                echo "<div class='appointment' style='background-color: " . $fullDayEvent["color"] . ";'>";
                                echo "<span class='full-day Hovericon' onclick='DisplayEventsModel(" . $fullDayEvent["id"] . ")'>" . $title . "<br/></span> <div class='containerAll' style='margin-top: 16px;'> <span class='descriptionEv'> Ganztägig </span>";
                                if ($fullDayEvent['date'] == $currentDate) {
                                    echo "<div class='iconsDV'><i class='bx bx-edit bx-sm Hovericon' style='margin-right: 7px;' onclick='openModalEdite(" . $fullDayEvent["id"] . ")'></i>";
                                    echo "<i class='bx bx-trash bx-sm Hovericon' onclick='deleteAppointment(" . $fullDayEvent["id"] . ")'></i></div>";
                                    echo "</div></div>";
                                } else {
                                    echo "<div style='margin-top: -5px;'><span> &nbsp;&nbsp;[mehrtägiger Termin]</span></div>";
                                    echo "</div></div>";
                                }
                            }
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
                <!-- Schleife für Stunden -->
                <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                    <tr>
                        <td style="width: 100px;"><?= str_pad($hour, 2, '0', STR_PAD_LEFT) ?>:00</td>
                        <?php for ($day = 1; $day <= 7; $day++) :
                            // Setzen des Datums auf den Anfang der aktuellen Woche
                            $startOfWeek = new DateTime();
                            $startOfWeek->setISODate($year, $week, $day);
                            $currentDate = $startOfWeek->format('Y-m-d');
                            $currentHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                            $nextHour = str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00:00';
                            $class = ($currentDate  == sprintf('%04d-%02d-%02d', date('Y'), date('n'), date('j'))) ? 'today' : '';
                        ?>
                            <td style='width: 170px;' class='date <?= $class ?>'>
                                <!-- Abrufen und Anzeigen von Ereignissen für diese Stunde und diesen Tag -->
                                <?php
                                // SQL-Abfrage, um Ereignisse für diese Stunde und diesen Tag zu erhalten
                                $sql = "SELECT a.*, c.* ,a.id  FROM appointments a LEFT JOIN categories c ON a.category_id = c.id WHERE (a.date = :date OR (:date BETWEEN a.date AND a.end_date)) AND ((a.start_time <= :start_time AND a.end_time > :start_time) OR (a.start_time >= :start_time AND a.start_time < :next_hour)) ORDER BY a.start_time";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':date', $currentDate);
                                $stmt->bindParam(':start_time', $currentHour);
                                $stmt->bindParam(':next_hour', $nextHour);
                                $stmt->execute();

                                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Anzeigen der Ereignisse für diese Stunde und diesen Tag
                                foreach ($events as $event) {
                                    $title = (strlen($event['title']) > 18) ? substr($event['title'], 0, 18) . '...' : $event['title'];
                                    echo "<div class='appointment' style='background-color: " . $event["color"] . ";'>";
                                    echo "<span class='timed Hovericon' onclick='DisplayEventsModel(" . $event["id"] . ")'>" . $title . " <br/> </span>";
                                    echo "<div class='containerAll'><span class='descriptionEv'>" . substr($event["start_time"], 0, 5);
                                    if (!empty($event["end_time"])) {
                                        // Anzeigen der Endzeit, falls vorhanden
                                        echo " - " . substr($event["end_time"], 0, 5);
                                    }
                                    echo "</span>";
                                    if ($event['date'] == $currentDate) {
                                        echo "<div class='iconsDV'><i class='bx bx-edit bx-sm Hovericon' style='margin-right: 7px;' onclick='openModalEdite(" . $event["id"] . ")'></i>";
                                        echo "<i class='bx bx-trash bx-sm Hovericon' onclick='deleteAppointment(" . $event["id"] . ")'></i></div>";
                                        echo "</div></div>";
                                    } else {
                                        echo "<div style='margin-top: -5px;'><span> &nbsp;&nbsp;[mehrtägiger Termin]</span></div>";
                                        echo "</div></div>";
                                    }
                                }
                                ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>

        <?php elseif ($view == 'day') : ?>
            <?php
            // Vorherige und nächste Tagberechnungen
            $prevDayTimestamp = strtotime("-1 day", mktime(0, 0, 0, $month, $day, $year));
            $nextDayTimestamp = strtotime("+1 day", mktime(0, 0, 0, $month, $day, $year));
            $prevDay = date('j', $prevDayTimestamp);
            $nextDay = date('j', $nextDayTimestamp);
            $currentDay = date('M j, Y', mktime(0, 0, 0, $month, $day, $year));
            $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

            // Berechnen des Tagesnummers im Jahr
            $dayOfYear = date('z', mktime(0, 0, 0, $month, $day, $year)) + 1;

            // Berechnen der vorherigen und nächsten Daten
            $prevDate = date('Y-m-d', strtotime('-1 day', strtotime($year . '-' . $month . '-' . $day)));
            $nextDate = date('Y-m-d', strtotime('+1 day', strtotime($year . '-' . $month . '-' . $day)));

            // Extrahieren des Jahres, Monats und Tages aus den vorherigen und nächsten Daten
            $prevYear = date('Y', strtotime($prevDate));
            $prevMonth = date('m', strtotime($prevDate));
            $nextYear = date('Y', strtotime($nextDate));
            $nextMonth = date('m', strtotime($nextDate));

            $dayName = date('D', mktime(0, 0, 0, $month, $day, $year));
            ?>

            <div class="navigation">
                <a href='?view=day&day=<?= $prevDay ?>&month=<?= $prevMonth ?>&year=<?= $prevYear ?>'>
                    < Vorheriger Tag</a>&nbsp;&nbsp;
                        <h3> Tag <?= $dayOfYear ?> : <?= $dayName ?> - <?= $currentDay ?></h3>
                        &nbsp;&nbsp;<a href='?view=day&day=<?= $nextDay ?>&month=<?= $nextMonth ?>&year=<?= $nextYear ?>'>Nächster Tag ></a>
            </div>

            <!-- Kalendertabelle -->
            <table>
                <tr>
                    <th>Zeit</th>
                    <th><?= $dayName ?></th> <!-- Anzeige des Wochentages -->
                </tr>
                <!-- Zeile für ganztägige Ereignisse -->
                <tr>
                    <td style='width: 240px;'>Ganztägig</td>
                    <?php
                    // Überprüfung, ob der aktuelle Tag heute ist, um entsprechende CSS-Klasse anzuwenden
                    $class = ($year . '-' . $month . '-' . $day  == sprintf('%04d-%02d-%02d', date('Y'), date('n'), date('j'))) ? 'today' : '';
                    ?>
                    <td class='date <?= $class ?>'>
                        <!-- Abrufen und Anzeigen von ganztägigen Ereignissen für diesen Tag -->
                        <?php
                        $sql = "SELECT a.*, c.* ,a.id FROM appointments  a LEFT JOIN categories c ON a.category_id = c.id WHERE DATE(a.date) <= :date AND (DATE(a.end_date) >= :date OR a.end_date IS NULL) AND a.is_full_day = 1";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':date', "$year-$month-$day");
                        $stmt->execute();
                        $fullDayEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($fullDayEvents as $fullDayEvent) {
                            echo "<div class='appointment' style='background-color: " . $fullDayEvent["color"] . ";'>";
                            echo "<span class='full-day Hovericon' onclick='DisplayEventsModel(" . $fullDayEvent["id"] . ")'>" . $fullDayEvent['title'] . "<br/></span> <div class='containerAll' style='margin-top: 16px;'> <span class='descriptionEv'> Ganztägig </span>";
                            if ($fullDayEvent['date'] == $currentDate) {
                                echo "<div class='iconsDV' style='margin-right: 50px;'><i class='bx bx-edit bx-sm Hovericon' style='margin-right: 7px;' onclick='openModalEdite(" . $fullDayEvent["id"] . ")'></i>";
                                echo "<i class='bx bx-trash bx-sm Hovericon' onclick='deleteAppointment(" . $fullDayEvent["id"] . ")'></i></div>";
                                echo "</div></div>";
                            } else {
                                echo "<div class='iconsDV' style='margin-right: 50px;'><span> [mehrtägiger Termin]</span></div>";
                                echo "</div></div>";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <!-- Schleife für Stunden -->
                <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                    <tr>
                        <td style='width: 240px;'><?= str_pad($hour, 2, '0', STR_PAD_LEFT) ?>:00</td>
                        <td class='date <?= $class ?>'>
                            <!-- Abrufen und Anzeigen von Ereignissen für diese Stunde und diesen Tag -->
                            <?php
                            $currentHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                            $nextHour = str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00:00';

                            // Abrufen aller Ereignisse für diese Stunde und diesen Tag
                            $sql = "SELECT a.*, c.* ,a.id FROM appointments a LEFT JOIN categories c ON a.category_id = c.id WHERE (a.date = :date OR (:date BETWEEN a.date AND a.end_date)) AND ((a.start_time <= :start_time AND a.end_time > :start_time) OR (a.start_time >= :start_time AND a.start_time < :next_hour)) ORDER BY a.start_time";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':date', "$year-$month-$day");
                            $stmt->bindParam(':start_time', $currentHour);
                            $stmt->bindParam(':next_hour', $nextHour);
                            $stmt->execute();

                            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($events as $event) {
                                echo "<div class='appointment' style='background-color: " . $event["color"] . ";'>";
                                echo "<span class='timed Hovericon' onclick='DisplayEventsModel(" . $event["id"] . ")'>" . $event['title'] . " <br/> </span>";
                                echo "<div class='containerAll'><span class='descriptionEv'>" . substr($event["start_time"], 0, 5);
                                if (!empty($event["end_time"])) {
                                    // Anzeige der Endzeit, wenn vorhanden
                                    echo " - " . substr($event["end_time"], 0, 5);
                                }
                                echo "</span>";
                                if ($event['date'] == $currentDate) {
                                    echo "<div class='iconsDV' style='margin-right: 50px;'><i class='bx bx-edit bx-sm Hovericon' style='margin-right: 7px;' onclick='openModalEdite(" . $event["id"] . ")'></i>";
                                    echo "<i class='bx bx-trash bx-sm Hovericon' onclick='deleteAppointment(" . $event["id"] . ")'></i></div>";
                                    echo "</div></div>";
                                } else {
                                    echo "<div class='iconsDV' style='margin-right: 50px;'><span> [mehrtägiger Termin]</span></div>";
                                    echo "</div></div>";
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </table>

        <?php endif; ?>

    </div>

    <script defer type="text/javascript" src="js/script.js"></script>
</body>

</html>