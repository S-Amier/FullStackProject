<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
   

    
    $servername = "localhost";
    $username = "root";
    $password = "";


    try {
        $db = new PDO("mysql:host=$servername;dbname=casuscafé", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //aparte form voor events toegevoegd
        if (isset($_POST['add_event'])) {
            $idEvent = $_POST["idMuziekavond"];
            $date = $_POST["datum"];
            $time = $_POST["aanvangsttijd"];
            $eventName = $_POST["eventnaam"];
            $price = $_POST["entreePrijs"];

            // info zetten in database(muziekavonden)
            $stmt = $db->prepare("INSERT INTO `muziekavonden` (`idMuziekavond`, `datum`, `eventnaam`, `aanvangsttijd`, `entreeprijs`) VALUES (:idMuziekavond, :datum, :eventnaam, :aanvangsttijd, :entreeprijs)");

            //parameters van muziekavonden
            $stmt->bindParam(':idMuziekavond', $idEvent);
            $stmt->bindParam(':datum', $date);
            $stmt->bindParam(':eventnaam', $eventName);
            $stmt->bindParam(':aanvangsttijd', $time);
            $stmt->bindParam(':entreeprijs', $price);

         

            //statement
            $stmt->execute();
            echo "Event geadd";
        }

        // aparte form voor bands toegevoegd
        if (isset($_POST['add_band'])) {
            $idBand = $_POST["idBand"];
            $bandName = $_POST["bandnaam"];
            $muziekgenre = $_POST["muziekgenre"];

            //info zetten in database(bands)
            $stmt = $db->prepare("INSERT INTO `bands` (`idBand`, `bandnaam`, `muziekgenre`) VALUES (:idBand, :bandnaam, :muziekgenre)");

            //parameters van bands
            $stmt->bindParam(':idBand', $idBand);
            $stmt->bindParam(':bandnaam', $bandName);
            $stmt->bindParam(':muziekgenre', $muziekgenre);

            //statement
            $stmt->execute();
            echo "Band geadd";
        }

        if (isset($_POST['add_band_to_event'])) {
            $idEvent = $_POST['idMuziekavond'];
            $idBand = $_POST['idBand'];


            //info in tussentabel plaatsen
            $stmt = $db->prepare("INSERT INTO `muziekavonden_has_bands` (`Muziekavonden_idMuziekavond`, `Bands_idBand`) VALUES (:idMuziekavond, :idBand)");
            $stmt->bindParam(':idMuziekavond', $idEvent);
            $stmt->bindParam(':idBand', $idBand);
            $stmt->execute();
            echo "Band succesvol gelinked aan muziekavond";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); 
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CasusCafé</title>
</head>
<body>
    <h1>Casus Café</h1>
    <h2> Event Aanmaken</h2>

<form action="index.php" method="post">
    <label for="idMuziekavond">idMuziekavond</label>
    <input type="text" name="idMuziekavond" id="idMuziekavond"><br>
    <label for="datum">Datum</label>
    <input type="text" name="datum" id="datum"><br>
    <label for="aanvangsttijd">aanvangsttijd</label>
    <input type="text" name="aanvangsttijd" id="aanvangsttijd"><br>
    <label for="eventnaam">eventnaam:</label>
    <input type="text" name="eventnaam" id="eventnaam"><br>
    <label for="entreeprijs">Entree Prijs</label>
    <input type="text" name="entreePrijs" id="entreeprijs"><br>
    <input type="hidden" name="add_event" value="1">
    <input type="submit" value="Event aanmaken">
</form>


    <h2> Band toevoegen</h2>

<form action="index.php"method="post">
    <label for="idBand">idBand</label>
    <input type="text" name="idBand" id="idBand"><br>
    <label for="bandnaam">Naam</label>
    <input type="text" name="bandnaam" id="bandnaam"><br>
    <label for="muziekgenre">Muziekgenre</label>
    <input type="text" name="muziekgenre" id="muziekgenre"><br>
    <input type="hidden" name="add_band" value="1">
    <input type="submit" value="Band toevoegen">
</form>

    <h2> Band aan muziekavonden linken</h2>

<form action="index.php"method="post">
    <label for="idBand">idMuziekavond</label>
    <input type="text" name="idMuziekavond" id="idMuziekavond"><br>
    <label for="idBand">idBand</label>
    <input type="text" name="idBand" id="idBand"><br>
    <input type="hidden" name="add_band_to_event" value="1">
    <input type="submit" value="Band aan muziekavond linken">
</form>
</body>
</html>