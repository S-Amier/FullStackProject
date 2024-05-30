<?php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=$servername;dbname=casuscafé", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT idMuziekavond, datum, eventnaam, aanvangsttijd, entreeprijs, bandnaam, muziekgenre 
                        FROM muziekavonden 
                        LEFT JOIN muziekavonden_has_bands ON muziekavonden_has_bands.muziekavonden_idMuziekavond = muziekavonden.idMuziekavond
                        LEFT JOIN bands ON muziekavonden_has_bands.bands_idBand = bands.idBand");

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Associatieve array om bands te koppelen
    $events = [];
    foreach ($results as $row) {
        $eventId = $row['idMuziekavond'];
        if (!isset($events[$eventId])) {
            $events[$eventId] = [
                'datum' => $row['datum'],
                'eventnaam' => $row['eventnaam'],
                'aanvangsttijd' => $row['aanvangsttijd'],
                'entreeprijs' => $row['entreeprijs'],
                'bands' => []
            ];
        }
        if ($row['bandnaam']) {
            $events[$eventId]['bands'][] = [
                'bandnaam' => $row['bandnaam'],
                'muziekgenre' => $row['muziekgenre']
            ];
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Events</title>
</head>
<body>
    <a href="index.php">Terug naar forms</a>
    <h1> Events </h1>
    <?php foreach ($events as $event): ?>
        <div class="event">
            <div class="event-details">
                <div class="event-info">
                    <div><?php echo $event['datum']; ?></div>
                    <div><?php echo $event['aanvangsttijd']; ?></div>
                </div>
                <div class="event-name">
                    <div><h2><?php echo $event['eventnaam']; ?></h2></div>
                </div>
                <div class="event-price">
                    <div><?php echo "Price: €" . $event['entreeprijs']; ?></div>
                </div>
            </div>
            <!-- Bands weergeven -->
            <div class="bands">
                <p>Bands die komen optreden:</p>
                <?php foreach ($event['bands'] as $band): ?>
                    <div><?php echo $band['bandnaam']; ?> - <?php echo $band['muziekgenre']; ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>