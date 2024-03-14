 <?php
// Standardmäßig auf den aktuellen Monat und das aktuelle Jahr setzen
$month = isset($_GET['month']) ? $_GET['month'] : date('n');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Anzahl der Tage im Monat
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Erster Tag des Monats
$firstDay = mktime(0, 0, 0, $month, 1, $year);

// Monats- und Wochentagsnamen erhalten
$monthName = date('F', $firstDay);
$dayOfWeek = date('N', $firstDay);

// Wochentage ab Montag
$daysOfWeek = ["Mon", "Die", "Mit", "Don", "Fre", "Sam", "Son"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PHP Kalender</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }

    .today {
        background-color: #ffff99;
    }

    .other-month {
        color: #999;
    }

    .navigation {
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .navigation a {
        padding: 10px 15px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .navigation a:hover {
        background-color: #555;
    }

    .navigation h2 {
        margin-left: 60px;
        margin-right: 60px;
    }

    td.date:hover {
        background-color: #e6e6e6;
        cursor: pointer;
    }
</style>
</head>
<body>

<!-- Navigation -->
<div class="navigation">
    <a href='?month=<?= ($month == 1) ? 12 : $month - 1 ?>&year=<?= ($month == 1) ? $year - 1 : $year ?>'>< Vorheriger</a>
    <h2><?= $monthName ?> <?= $year ?></h2>
    <a href='?month=<?= ($month == 12) ? 1 : $month + 1 ?>&year=<?= ($month == 12) ? $year + 1 : $year ?>'>Nächster ></a>
</div>

<!-- Kalendertabelle -->
<table>
    <tr>
        <?php foreach ($daysOfWeek as $day): ?>
            <th><?= $day ?></th>
        <?php endforeach; ?>
    </tr>

    <tr>
        <?php
        // Füge Zellen für die Tage vor dem ersten Tag des Monats hinzu
        for ($i = 1; $i < $dayOfWeek; $i++) {
            echo "<td class='other-month'>" . (date('t', mktime(0, 0, 0, $month - 1, 1, $year)) - ($dayOfWeek - $i - 1)) . "</td>";
        }

        // Durchlaufe jeden Tag des Monats
        for ($day = 1; $day <= $daysInMonth; $day++) {

            $class = ($day == date('j') && $month == date('n') && $year == date('Y')) ? 'today' : '';
            echo "<td class='date $class'>$day</td>";

            if (date('N', mktime(0, 0, 0, $month, $day, $year)) == 7) {
                echo "</tr><tr>";
            }
        }

        // Füge Zellen für Tage nach dem letzten Tag des Monats hinzu
        $lastDayOfWeek = date('N', mktime(0, 0, 0, $month, $daysInMonth, $year));
        for ($i = $lastDayOfWeek + 1; $i <= 7; $i++) {
            $nextMonthDay = $i - $lastDayOfWeek;
            echo "<td class='other-month'>$nextMonthDay</td>";
        }
        ?>
    </tr>
</table>
</body>
</html>