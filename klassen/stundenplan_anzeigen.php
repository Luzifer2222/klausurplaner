<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
$datenbank->set_charset('utf8');


// Abfrage der Klasse
$klassenQuery = "SELECT klassenID, name FROM klassen;";
$planQuery = "Select * from stunde;";
$fachQuery = "Select * from faecher;";
$klassenErgebnis = $datenbank->query($klassenQuery);
$planErgebnis = $datenbank->query($planQuery);
$fachErgebnis = $datenbank->query($fachQuery);

?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="planform" name="auswahl">
		<fieldset>
		<legend>Stundenplan der Klasse</legend>
		<p>
		<label for="klassenwahl">&nbsp;</label> <select name="klassenwahl">
		<?php
		while ($daten = $klassenErgebnis->fetch_object())
		{
			echo "<option value=\"$daten->klassenID\">$daten->name</option>";
		}
		?>
		</select>
		<input type="submit" name="anzeigen" value="Anzeigen">
		</p>
	</fieldset>
</form>
<hr>

<?php
// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Anzeigen des Stundenplans der gewählten Klasse
// aus der Tabelle Stunden
if (isset($_POST['anzeigen']))
{	
	echo "<table class=\"plantable\" id=\"stundenplan\">";
	
	// Abfrage der Namen der ausgewählten Klasse und dem zugehörigem Klassenlehrer aus der Tabelle Klassen
	$classQuery = "SELECT name FROM klassen Where klassenID = " . $_POST['klassenwahl'] . ";";
	$classErgebnis = $datenbank->query($classQuery);
	while ($daten = $classErgebnis->fetch_object())
	{
		$klassenname = $daten->name;
	}
	$teacherQuery = "SELECT l.nachname, l.vorname FROM klassen k, lehrer l Where klassenID = " . $_POST['klassenwahl'] . " AND k.klassenlehrerID = l.lehrerID;";
	$teacherErgebnis = $datenbank->query($teacherQuery);
	while ($daten = $teacherErgebnis->fetch_object())
	{
		$lehrervorname = $daten->vorname;
		$lehrernachname  = $daten->nachname;
	}
	
	// Ausgabe des Stundenplans zu der ausgewählten Klasse
	echo "<caption>Klasse: <b>" . $klassenname . "</b> Klassenlehrer: <b>" . $lehrervorname . " " . $lehrernachname . "</b></caption>";
	echo "<tr>";
	echo "<th class=\"vonbis\">Stunde</th>";
	echo "<th>Montag</th>";
	echo "<th>Dienstag</th>";
	echo "<th>Mittwoch</th>";
	echo "<th>Donnerstag</th>";
	echo "<th>Freitag</th>";
	echo "</tr>";
	
	// Abfrage der Stunden der gewählten Klasse aus der Tabelle Stunden
	$sQuery = "Select klassenID, name from stunden ";
	$sQuery .= "where klassenID = '" . $_POST['klassenwahl'] . "';";
	$sErgebnis = $datenbank->query($sQuery);
	
	// Erste bis achte Schulstunde, falls es mehr gibt, muss dieser erhöht werden
	for ($schulstunde = 1 ; $schulstunde < 13 ; $schulstunde++)
	{
		echo "<tr>";
		echo "<td>$schulstunde</td>";
		
		// Montag bis Freitag, falls es Samstags Unterricht gibt, muss dieser Wert erhöht werden
		for ($tag = 1 ; $tag < 6 ; $tag++)
		{
			
			// Einfache Abfrage
			$fragestunde = "select stunde ";
			$fragestunde .= "from stunden ";
			$fragestunde .= "where klassenID = " . $_POST['klassenwahl'] . " AND wochentag = $tag AND stunde = $schulstunde;";
			
			// Ergebnis der Abfrage aus $fragestunde
			$ergfragestunde = $datenbank->query($fragestunde);
			$pruefe = NULL;
			while ($daten = $ergfragestunde->fetch_object())
			{
				$pruefe = $daten->stunde;
			}
			if ($schulstunde == $pruefe)
			{
				
				// Einfache Abfrage
				$fQuery = "Select f.name ";
				$fQuery .= "from faecher f, stunden s ";
				$fQuery .= "where s.klassenID = " . $_POST['klassenwahl'] . " AND s.wochentag = $tag AND s.stunde = $schulstunde AND f.fachID = s.fachID;";
				
				// Ergebnis der Abfrage aus $fQuery
				$fachErg = $datenbank->query($fQuery);
				while ($daten = $fachErg->fetch_object())
				{
					echo "<td>$daten->name</td>";
				}
			}
			else
			{
				echo "<td>&nbsp;</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";
	echo "<hr>";
}
?>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>