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

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="formular" name="auswahl">
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
		<input type="submit" name="aendern" value="Ändern">
		<?php if (isset($_POST['aendern'])): ?>
		<input type="submit" name="speichern" value="Speichern">
		<?php endif ?>
		</p>
	</fieldset>
</form>

<?php
// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Ändern des Stundenplans
if (isset($_POST['aendern']))
{
	echo "<hr>";
	echo "<form action=\"\" method=\"post\" name=\"tabelle\" class=\"formular\">";
	echo "<table class=\"kalender\" id=\"stundenplan\">";
		
	// Abfrage des Namens der ausgewählten Klasse aus der Tabelle Klassen
	$classQuery = "SELECT name FROM klassen Where klassenID = " . $_POST['klassenwahl'] . ";";
	$classErgebnis = $datenbank->query($classQuery);
	while ($daten = $classErgebnis->fetch_object())
	{
		$klassenname = $daten->name;
	}
	
	// Ausgabe des Stundenplans zu der ausgewählten Klasse
	echo "<caption>Klasse " . $klassenname . "</caption>";
	echo "<tr>";
	echo "<th class=\"vonbis\">Stunde</th>";
	echo "<th>Montag</th>";
	echo "<th>Dienstag</th>";
	echo "<th>Mittwoch</th>";
	echo "<th>Donnerstag</th>";
	echo "<th>Freitag</th>";
	echo "</tr>\n";
	// Abfrage der Stunden der gewählten Klasse aus der Tabelle Stunden
	$sQuery = "Select klassenID, name from stunden ";
	$sQuery .= "where klassenID = '" . $_POST['klassenwahl'] . "';";
	$sErgebnis = $datenbank->query($sQuery);
	
	// Hochzählender Eintrag zur Abfrage der einzelnen Felder
	$wert = 1;
	$eintrag = "feld";
	
	// Erste bis achte Schulstunde, falls es mehr gibt, muss dieser erhöht werden
	for ($schulstunde = 1 ; $schulstunde < 9 ; $schulstunde++)
	{
		echo "<tr>";
		echo "<td>$schulstunde</td>\n";
		
		// Montag bis Freitag, falls es Samstags Unterricht gibt, muss dieser Wert erhöht werden
		for ($tag = 1 ; $tag < 6 ; $tag++)
		{
			
			// Einfache Abfrage
			$fragestunde = "select f.name ";
			$fragestunde .= "from faecher f, stunden s ";
			$fragestunde .= "where s.klassenID = " . $_POST['klassenwahl'] . " AND s.wochentagID = $tag AND s.stunde = $schulstunde AND f.fachID = s.fachID;";
			
			// Ergebnis der Abfrage aus $fragestunde
			$ergfragestunde = $datenbank->query($fragestunde);
			$pruefe = NULL;
			$boolean = false;
			// Benennung der Felder durch den hochzählenden Eintrag zur Abfrage der einzelnen Felder
			$feld = "$eintrag$wert";
			while ($daten = $ergfragestunde->fetch_object())
			{
				$pruefe = $daten->name;
			}
			echo "<td><select class=\"plan\" name=\"$feld\">";
				$fachErgebnis = $datenbank->query($fachQuery);
				while ($daten = $fachErgebnis->fetch_object())
				{
					echo "<option value=\"$daten->fachID\" ";
					if ($daten->name == $pruefe)
					{
						echo "selected=\"selected\"";
						$boolean = true;
					}
					echo " >" . $daten->name . "</option>";
				}
				if ($boolean == false)
				{
					echo "<option value=\"0\" selected=\"selected\">&nbsp;</option>";
				}
				else
				{
					echo "<option value=\"0\">&nbsp;</option>";
				}
			echo "</select></td>\n";
			// Hochzählender Eintrag zur Abfrage der einzelnen Felder
			$wert++;
		}
	echo "</tr>";
	}
	echo "</table>";
	echo "</form>";
}
?>

<?php
// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Anzeigen des Stundenplans der gewählten Klasse
// aus der Tabelle Stunden
if (isset($_POST['anzeigen']))
{	
	echo "<hr>";
	echo "<table class=\"kalender\" id=\"stundenplan\">";
	
	// Abfrage des Namens der ausgewählten Klasse aus der Tabelle Klassen
	$classQuery = "SELECT name FROM klassen Where klassenID = " . $_POST['klassenwahl'] . ";";
	$classErgebnis = $datenbank->query($classQuery);
	while ($daten = $classErgebnis->fetch_object())
	{
		$klassenname = $daten->name;
	}
	
	// Ausgabe des Stundenplans zu der ausgewählten Klasse
	echo "<caption>Klasse " . $klassenname . "</caption>";
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
	for ($schulstunde = 1 ; $schulstunde < 9 ; $schulstunde++)
	{
		echo "<tr>";
		echo "<td>$schulstunde</td>";
		
		// Montag bis Freitag, falls es Samstags Unterricht gibt, muss dieser Wert erhöht werden
		for ($tag = 1 ; $tag < 6 ; $tag++)
		{
			
			// Einfache Abfrage
			$fragestunde = "select stunde ";
			$fragestunde .= "from stunden ";
			$fragestunde .= "where klassenID = " . $_POST['klassenwahl'] . " AND wochentagID = $tag AND stunde = $schulstunde;";
			
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
				$fQuery .= "where s.klassenID = " . $_POST['klassenwahl'] . " AND s.wochentagID = $tag AND s.stunde = $schulstunde AND f.fachID = s.fachID;";
				
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
}
?>

<?php
// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Speichern des Stundenplans
// in die Tabelle Stunden

if (isset($_POST['speichern']))
{
	// Hochzählender Eintrag zur Abfrage der einzelnen Felder
	$wert = 1;
	$eintrag = "feld";
	for ($schulstunde = 1 ; $schulstunde < 9 ; $schulstunde++)
	{
		for ($tag = 1 ; $tag < 6 ; $tag++)
		{
			$feld = "$eintrag$wert";
			if (($_POST["$feld"]) == 0)
			{
				
			}
			$wert++;
		}
	}
		
	if (($_POST['feld1']) == 2)
	{
		echo "test2";
	}
	if (($_POST['feld1']) == 1)
	{
		echo "test1";
	}
}
?>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>
</html>