<?php

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

if (isset($_POST['loescheklasse']) && isset($_POST['loesche']))
{
	$loescheQuery = "delete from klassen where klassenID = " . $_POST['loesche'];
	
	$datenbank->query($loescheQuery);
	
	if ($datenbank->affected_rows > 0)
	{
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein Datensatz gelöscht</p>";
	}
}

// Einfügen der neuen Klasse
if (isset($_POST['klasseanlegen']))
{
	if ($_POST['klasse'] != "")
	{
		// Erstellen der Einfügeanweisung in SQL
		$insertQuery = "INSERT INTO klassen ";
		$insertQuery .= "(name, klassenlehrerID) values";
		$insertQuery .= "('" . mysql_real_escape_string($_POST['klasse']) . "', '" . $_POST['klassenlehrer'] . "');";
		
		// Einfügen der Formulardaten in die Lehrertabelle
		$datenbank->query($insertQuery);
		
		// Überprüfung ob der Datensatz angelegt wurde
		if ($datenbank->affected_rows > 0)
		{
			// Speichern des Erfolgsstrings in eine Variable
			$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde eine neue Klasse angelegt.</p>";
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde keine neue Klasse angelegt.</p>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Alle Felder müssen ausgefüllt werden!</p>";
	}
}

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Ändern des Lehrers
// in der Tabelle Klassen
if (isset($_POST['aendernlehrer']) && isset($_POST['checkaendern']))
{
	// Einfache Abfragen
	$fragelehrer = "select klassenlehrerID ";
	$fragelehrer .= "from klassen ";
	$fragelehrer .= "where klassenID = '" . $_POST['checkaendern'] . "';";

	// Ergebnis der Abfrage aus $fragelehrer
	$ergfragelehrer = $datenbank->query($fragelehrer);
		
	while ($daten = $ergfragelehrer->fetch_object())
	{
		$pruefe = $daten->klassenlehrerID;
	}
	
	if ($_POST['neuerlehrer'] != $pruefe)
	{
		// Erstellen der Einfügeanweisung in SQL
		$insertquery = "UPDATE klassen ";
		$insertquery .= "SET klassenlehrerID = '" . $_POST['neuerlehrer'] . "'";
		$insertquery .= " WHERE klassenID = '" . $_POST['checkaendern'] . "';";
		
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
}

// Abfrage Bereich
$abfrageLehrer = "select * from lehrer;";
$abfrageFach = "select k.klassenID, k.name, l.vorname, l.nachname, l.lehrerID from klassen k, lehrer l ";
$abfrageFach .= "where k.klassenlehrerID = l.lehrerID;";
$abfrageKlasse = "SELECT klassenID, name from klassen;";

// Ergebnisse der Abfragen
$ergebnisLehrer1 = $datenbank->query($abfrageLehrer);
$ergebnisFach = $datenbank->query($abfrageFach);
$ergebnisKlasse = $datenbank->query($abfrageKlasse);

?>
<form class="anlegen" action="" method="post">
	<fieldset>
		<legend>Klasse anlegen</legend>
		<p>
			<label for="klasse">Klasse:</label><input type="text" pattern="[A-Za-z0-9]{1,6}[ ]{0,1}[-]{0,1}[A-Za-z0-9]{1,6}[ ]{0,1}[-]{0,1}[A-Za-z0-9]{1,6}" min="4" maxlength="20" id="klasse" name="klasse">
		</p>
		<p>
			<label for="klassenlehrer">Klassenlehrer:</label> <select id="klassenlehrer" name="klassenlehrer">
							<?php
							while ($daten = $ergebnisLehrer1->fetch_object())
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

<?php
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>
<hr>
<form action="" method="post">
	<table class="ausgabe">
	<caption>Angelegte Klassen:</caption>
		<tr>
			<th>KlassenID</th>
			<th>Klassenname</th>
			<th>Klassenlehrer</th>
			<th><input type="submit" name="loescheklasse" value="Löschen"></th>
			<th><input type="submit" name="aendernlehrer" value="Ändern"></th>
		</tr>
				<?php
				while ($daten = $ergebnisFach->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $daten->klassenID . "</td>";
					echo "<td>" . $daten->name . "</td>";
					echo "<td><select class=\"aendern\" name=\"neuerlehrer\">";
					$ergebnisLehrer2 = $datenbank->query($abfrageLehrer);
					while ($lehrertabelle = $ergebnisLehrer2->fetch_object())
					{
						echo "<option value=\"$lehrertabelle->lehrerID\" ";
						if ($lehrertabelle->lehrerID == $daten->lehrerID)
						{
							echo "selected=\"selected\" ";
						}
						echo ">" . $lehrertabelle->vorname . " " . $lehrertabelle->nachname . "</option>";
					}
					echo "</select></td>";
					echo "<td><input type=\"radio\" name=\"loesche\" value=\"" . $daten->klassenID . "\"></td>";
					echo "<td><input type=\"radio\" name=\"checkaendern\" value=\"" . $daten->klassenID . "\"></td>";
					echo "</tr>";
				}
				?>
				<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<th><input type="submit" name="loescheklasse" value="Löschen"></th>
			<th><input type="submit" name="aendernlehrer" value="Ändern"></th>
	
	</table>
</form>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>