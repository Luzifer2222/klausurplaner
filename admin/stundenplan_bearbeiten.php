<?php
// Titel:           stundenplan_bearbeiten.php
// Version:         1.0
// Autor:			PHPmeetsSQL
// Datum:           20.05.15
// Beschreibung:    Zuständig für das Bearbeiten der Stundenpläne

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Abfrage der Klasse
$klassenQuery = "SELECT klassenID, name FROM klassen;";
$planQuery = "Select * from stunde;";
$fachQuery = "Select * from faecher;";
$klassenErgebnis = $datenbank->query($klassenQuery);
$planErgebnis = $datenbank->query($planQuery);
$fachErgebnis = $datenbank->query($fachQuery);

?>

<form action="" method="post" class="planform" name="auswahl">
	<fieldset>
		<legend>Auswahl der Klasse</legend>
		<p>
		<label for="klassenwahl">&nbsp;</label> <select name="klassenwahl">
		<?php
		while ($daten = $klassenErgebnis->fetch_object())
		{
			echo "<option value=\"$daten->klassenID\">$daten->name</option>";
		}
		?>
		</select>
		<input type="submit" name="aendern" value="Ändern">
		<?php if ((isset($_POST['aendern'])) OR (isset($_POST['speichern']))): ?>
		<input type="submit" name="speichern" value="Speichern">
		<?php endif ?>
		</p>
	</fieldset>
<hr>
<?php

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Speichern des Stundenplans
// in die Tabelle Stunden
if (isset($_POST['speichern']))
{
	// Hochzählender Eintrag zur Abfrage der einzelnen Felder
	$wert = 1;
	$eintrag = "feld";
	
	// Schleife für 12 Schulstunden
	for ($schulstunde = 1 ; $schulstunde < 13 ; $schulstunde++)
	{
		// Schleife für fünf Schultage (Montag bis Freitag)
		for ($tag = 1 ; $tag < 6 ; $tag++)
		{
			$feld = "$eintrag$wert";
			
			$pruefe = NULL;
			
			// Einfache Abfragen
			$frageeintrag = "select fachID ";
			$frageeintrag .= "from stunden ";
			$frageeintrag .= "where stunde = $schulstunde AND wochentag = $tag AND klassenID = '" . $_POST['klassenwahl'] . "';";
			
			// Ergebnis der Abfrage aus $frageeintrag
			$ergfrageeintrag = $datenbank->query($frageeintrag);
			
			while ($daten = $ergfrageeintrag->fetch_object())
			{
				$pruefe = $daten->fachID;
			}
			
			if (($_POST["$feld"]) != 0)
			{
				if ($pruefe == NULL)
				{
					// Erstellen der Einfügeanweisung in SQL
					$insertquery = "insert into stunden ";
					$insertquery .= "(stunde, wochentag, fachID, klassenID) values";
					$insertquery .= "('" . $schulstunde . "', '" . $tag . "', '" . $_POST["$feld"] . "', '" . $_POST['klassenwahl'] . "');";
				}
				else
				{
					// Erstellen der Einfügeanweisung in SQL
					$insertquery = "UPDATE stunden ";
					$insertquery .= "SET fachID = '" . $_POST["$feld"] . "'";
					$insertquery .= " where stunde = $schulstunde AND wochentag = $tag AND klassenID = '" . $_POST['klassenwahl'] . "';";					
				}
				
				// Einfügen der Formulardaten in die Lehrertabelle
				$datenbank->query($insertquery);
			}
			else
			{
				// Löschen des Eintrages in der Tabelle Stunden
				$loescheQuery = "delete from stunden where stunde = $schulstunde AND wochentag = $tag AND klassenID = '" . $_POST['klassenwahl'] . "';";
				$datenbank->query($loescheQuery);
			}
			$wert++;
		}
	}
	// Speichern des Ausgabestrings in eine Variable
	$ausgabe = "<p class=\"erfolgreich\">Die Änderungen wurden gespeichert.</p><hr>";
}
?>
	
<?php
// Ausgabe ob Eintrag in die Datenbank erfolgreich war.
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>
	
<?php
// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Ändern des Stundenplans
if (isset($_POST['aendern']) OR isset($_POST['speichern']))
{
	echo "<table class=\"plantable\" id=\"stundenplan\">";
		
	// Abfrage des Namens der ausgewählten Klasse aus der Tabelle Klassen
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
	echo "<caption>Stundenplan der Klasse: <b>" . $klassenname . "</b> Klassenlehrer: <b>" . $lehrervorname . " " . $lehrernachname . "</b></caption>";
	echo "<tr>";
	echo "<th>Stunde</th>";
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
	
	// Schleife für zwölf Schulstunden
	for ($schulstunde = 1 ; $schulstunde < 13 ; $schulstunde++)
	{
		echo "<tr>";
		echo "<td>$schulstunde</td>\n";
		
		// Schleife für fünf Schultage (Montag bis Freitag)
		for ($tag = 1 ; $tag < 6 ; $tag++)
		{
			
			// Einfache Abfrage
			$fragestunde = "select f.name ";
			$fragestunde .= "from faecher f, stunden s ";
			$fragestunde .= "where s.klassenID = " . $_POST['klassenwahl'] . " AND s.wochentag = $tag AND s.stunde = $schulstunde AND f.fachID = s.fachID;";
			
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
	echo "<hr>";
}
?>
</form>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>