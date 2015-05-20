<?php
// Titel:           gewichtung_anlegen.php
// Version:         1.0
// Autor:			PHPmeetsSQL
// Datum:           20.05.15
// Beschreibung:    Zuständig für das Ändern der Klausur-/Test-Gewichtung

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

?>

<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post" class="formular">
	<fieldset>
		<legend>Klausur und Test Begrenzung</legend>
		<p>
			<label for="kmaxtag">Max. Klausuren Tag:</label><input type="number" min="1" max="10" maxlength="2" name="kmaxtag" id="kmaxtag" value="1">
		</p>
		<p>
			<label for="kmaxwoche">Max. Klausuren Woche:</label><input type="number" min="1" max="10" maxlength="2" name="kmaxwoche" id="kmaxwoche" value="1">
		</p>
		<p>
			<label for="tmaxtag">Max. Tests Tag:</label><input type="number" min="1" max="10" maxlength="2" name="tmaxtag" id="tmaxtag" value="1">
		</p>
		<p>
			<label for="tmaxwoche">Max. Tests Woche:</label><input type="number" min="1" max="10" maxlength="2" name="tmaxwoche" id="tmaxwoche" value="1">
		</p>
		<p>
		<input type="submit" name="speichern" value="Speichern">
		</p>
	</fieldset>
</form>

<?php

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Aktualisieren der Gewichtung
// in die Tabelle Anzahlklausurtest
if (isset($_POST['speichern']))
{	
	if (($_POST['kmaxtag'] <= $_POST['kmaxwoche']) AND ($_POST['tmaxtag'] <= $_POST['tmaxwoche']))
	{
		// Erstellen der Updateanweisung in SQL
		$insertquery = "UPDATE anzahlklausurtest ";
		$insertquery .= "SET maxklausurtag = " . ($_POST['kmaxtag']) . ", maxklausurwoche = " . ($_POST['kmaxwoche']) . ", maxtesttag = " . ($_POST['tmaxtag']) . ", maxtestwoche = " . ($_POST['tmaxwoche']) . "";
		$insertquery .= " WHERE anzahlID = '1';";
	
		// Einfügen der Formulardaten in die Lehrertabelle
		$datenbank->query($insertquery);
			
		// Überprüfung ob der Datensatz angelegt wurde
		if ($datenbank->affected_rows > 0)
		{
			// Speichern des Ausgabestrings in eine Variable
			$ausgabe = "<hr><p class=\"erfolgreich\">Die Gewichtung wurde geändert.</p>";
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Die Gewichtung konnte nicht geändert werden!</p>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Die Anzahl pro Tag darf nicht über der Anzahl pro Woche liegen!</p>";
	}		
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
// Abfrage der Klasse
$anzahlQuery = "SELECT * FROM anzahlklausurtest;";
$anzahlErgebnis = $datenbank->query($anzahlQuery);
?>

<hr>
<table class="ausgabe">
	<caption>Momentane Begrenzung:</caption>
	<tr>
		<th class="ausgabe_gewichtung">Max. Klausuren Tag</th>
		<th class="ausgabe_gewichtung">Max. Klausuren Woche</th>
		<th class="ausgabe_gewichtung">Max. Tests Tag</th>
		<th class="ausgabe_gewichtung">Max. Tests Woche</th>
	</tr>
	<?php
		while ($daten = $anzahlErgebnis->fetch_object())
		{
			echo "<tr>";
			echo "<td>" . $daten->maxklausurtag . "</td>";
			echo "<td>" . $daten->maxklausurwoche . "</td>";
			echo "<td>" . $daten->maxtesttag . "</td>";
			echo "<td>" . $daten->maxtestwoche . "</td>";
			echo "</tr>";
		}
	?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>