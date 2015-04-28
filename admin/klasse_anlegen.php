<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich()?>


<?php
// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
$datenbank->set_charset('utf8');

if (isset($_POST['loescheklasse']) && isset($_POST['loesche']))
{
	$loescheQuery = "delete from klassen where klassenID = " . $_POST['loesche'];
	
	$datenbank->query($loescheQuery);
	
	if ($datenbank->affected_rows > 0)
	{
		$ausgabe = "<p class=\"erfolgreich\">Es wurde der Datensatz mit der ID " . $_POST['loesche'] . " gelöscht.</p><hr>";
	}
}

// Einfügen der neuen Klasse
if (isset($_POST['klasseanlegen']))
{
	if ($_POST['klasse'] != "")
	{
		$insertQuery = "INSERT INTO klassen ";
		$insertQuery .= "(name, klassenlehrerID) values";
		$insertQuery .= "('" . $_POST['klasse'] . "', '" . $_POST['klassenlehrer'] . "');";
		
		$datenbank->query($insertQuery);
		
		if ($datenbank->affected_rows > 0)
		{
			// Speichern des Erfolgsstrings in eine Variable
			$ausgabe = "<p class=\"erfolgreich\">Es wurde 1 Datensatz angelegt.</p><hr>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<p class=\"error\">Alle Felder müssen ausgefüllt werden!</p><hr>";
	}
}

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Ändern des Lehrers
// in der Tabelle Klassen
if (isset($_POST['aendern']))
{
	// Erstellen der Einfüge anweisung in SQL
	$insertquery = "UPDATE klassen ";
	$insertquery .= "SET klassenlehrerID = '" . $_POST['lehrer'] . "'";
	$insertquery .= " WHERE klassenID = '" . $_POST['klassenauswahl'] . "'";
	
	// Einfügen der Formulardaten in die Klassentabelle
	$datenbank->query($insertquery);
	
	// Überprüfung ob der Datensatz angelegt wurde
	if ($datenbank->affected_rows > 0)
	{
		// Speichern des Ausgabestrings in eine Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Der zugehörige Klassenlehrer wurde geändert.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Der Klassenlehrer konnte nicht geändert werden!</p>";
	}
}


// Abfrage Bereich
$abfrageLehrer = "select * from lehrer;";
$abfrageFach = "select k.klassenID, k.name, l.vorname, l.nachname from klassen k, lehrer l ";
$abfrageFach .= "where k.klassenlehrerID = l.lehrerID;";
$abfrageKlasse = "SELECT klassenID, name from klassen;";

// Ergebnisse der Abfragen
$ergebnisLehrer = $datenbank->query($abfrageLehrer);
$ergebnisFach = $datenbank->query($abfrageFach);
$ergebnisKlasse = $datenbank->query($abfrageKlasse);

?>
<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<fieldset>
		<legend>Klasse anlegen</legend>
		<p>
			<label for="klasse">Klasse:</label><input type="text" id="klasse" name="klasse">
		</p>
		<p>
			<label for="klassenlehrer">Klassenlehrer:</label> <select id="klassenlehrer" name="klassenlehrer">
							<?php
							while ($daten = $ergebnisLehrer->fetch_object())
							{
								echo "<option value=\"$daten->lehrerID\">" . $daten->vorname . " " . $daten->nachname . "</option>";
							}
							?>
						</select>
		</p>
		<p>
			<label>&nbsp;</label><input type="submit" value="Klasse anlegen" name="klasseanlegen"> <input type="reset" value="Zurücksetzen" name="zuruecksetzen">
		</p>
	</fieldset>
</form>
<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post" name="lehreraendern" class="lehreraendern">
	<fieldset>
		<legend>Ändern des Klassenlehrers</legend>
		<p>
			<label for="klassenauswahl">Klasse:</label> <select id="klassenauswahl" name="klassenauswahl">
							<?php
							$ergebnisKlasse = $datenbank->query($abfrageKlasse);
							while ($daten = $ergebnisKlasse->fetch_object())
							{
								echo "<option value=\"$daten->klassenID\">" . $daten->name . "</option>";
							}
							?>
						</select>
		</p>
		<p>
			<label for="lehrer">Klassenlehrer:</label> <select id="lehrer" name="lehrer">
							<?php
							$ergebnisLehrer = $datenbank->query($abfrageLehrer);
							while ($daten = $ergebnisLehrer->fetch_object())
							{
								echo "<option value=\"$daten->lehrerID\">" . $daten->vorname . " " . $daten->nachname . "</option>";
							}
							?>
						</select>
		</p>
		<p class="button">
			<label>&nbsp;</label><input type="submit" name="aendern" value="Lehrer ändern"> <input type="reset" name="reset" value="Zurücksetzen">
		</p>
	</fieldset>
</form>

<?php
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>
<hr>
<table class="ausgabe">
	<tr>
		<th>KlassenID</th>
		<th>Klassenname</th>
		<th>Klassen Lehrer</th>
		<th><form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
				<input type="submit" value="Löschen" name="loescheklasse" /></th>
	</tr>
				<?php
				while ($daten = $ergebnisFach->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $daten->klassenID . "</td>";
					echo "<td>" . $daten->name . "</td>";
					echo "<td>" . $daten->vorname . " " . $daten->nachname . "</td>";
					echo "<td><input type=\"radio\" value=\"$daten->klassenID\" name=\"loesche\" />";
					echo "</tr>";
				}
				?>
				<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="submit" value="Löschen" name="loescheklasse" />
			</form></td>

</table>

<?php
$datenbank->close();
?>