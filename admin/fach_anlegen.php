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

// Überprüfung ob der Lösche-Button gedrückt wurde
if (isset($_POST['loeschefach']) && isset($_POST['loesche']))
{
	// Speichern der delete Abfrage und Durchführung der Abfrage
	$loescheQuery = "delete from faecher where fachID = " . $_POST['loesche'];
	$datenbank->query($loescheQuery);
	
	// Überprüfung ob der Datensatz gelöscht wurde
	if ($datenbank->affected_rows > 0)
	{
		// Speichern des Ausgabestrings in eine Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein Datensatz gelöscht</p>";
	}
}

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Einfügen des neuen Fachs
// in die Fächer Tabelle
if (isset($_POST['fachanlegen']))
{
	// Überprüfung ob das Feld des Einfügeformulars ausgefüllt wurde
	if ($_POST['fach'] != "")
	{
		// Erstellen der Einfügeanweisung in SQL
		$insertQuery = "INSERT INTO faecher ";
		$insertQuery .= "(name) values";
		$insertQuery .= "('" . mysql_real_escape_string(strtoupper($_POST['fach'])) . "');";
		
		// Einfügen der Formulardaten in die Lehrertabelle
		$datenbank->query($insertQuery);
		
		// Überprüfung ob der Datensatz angelegt wurde
		if ($datenbank->affected_rows > 0)
		{
			// Speichern des Erfolgsstrings in eine Variable
			$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde ein neues Fach angelegt.</p>";
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein neues Fach angelegt.</p>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Alle Felder müssen ausgefüllt werden!</p>";
	}
}

// Abfrage Bereich
$abfrageFach = "select * from faecher;";

// Ergebnisse der Abfragen
$ergebnisFach = $datenbank->query($abfrageFach);

?>

<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<fieldset>
		<legend>Fach anlegen</legend>
		<p>
			<label for="fach">Fach:</label><input type="text" pattern="[A-z0-9ÄÖÜäöü]{2,50}[ -]{0,10}" min="4" maxlength="50" id="fach" name="fach">
		</p>
		<p>
			<label>&nbsp;</label><input type="submit" value="Fach anlegen" name="fachanlegen"> <input type="reset" value="Zurücksetzen" name="zuruecksetzen">
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
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table class="ausgabe">
		<caption>Angelegte Fächer:</caption>
		<tr>
			<th>FachID</th>
			<th>Fachname</th>
			<th><input type="submit" value="Löschen" name="loeschefach" /></th>
		</tr>
				<?php
				while ($daten = $ergebnisFach->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $daten->fachID . "</td>";
					echo "<td>" . $daten->name . "</td>";
					echo "<td><input type=\"radio\" value=\"$daten->fachID\" name=\"loesche\" />";
					echo "</tr>";
				}
				?>
				<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" value="Löschen" name="loeschefach" /></td>
	
	</table>
</form>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>