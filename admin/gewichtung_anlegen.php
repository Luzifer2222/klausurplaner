<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');


// Abfrage der Klasse
$anzahlQuery = "SELECT * FROM anzahlklausurtest;";
$anzahlErgebnis = $datenbank->query($anzahlQuery);

?>

<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post" class="formular">
	<fieldset>
		<legend>Klausur und Test Begrenzung</legend>
		<p>
			<label for="kmaxtag">Max. Klausuren Tag:</label><input type="number" min="0" max="10" maxlength="2" name="kmaxtag" id="kmaxtag">
		</p>
		<p>
			<label for="kmaxwoche">Max. Klausuren Woche:</label><input type="number" min="0" max="10" maxlength="2" name="kmaxwoche" id="kmaxwoche">
		</p>
		<p>
			<label for="tmaxtag">Max. Tests Tag:</label><input type="number" min="0" max="10" maxlength="2" name="tmaxtag" id="tmaxtag">
		</p>
		<p>
			<label for="tmaxwoche">Max. Tests Woche:</label><input type="number" min="0" max="10" maxlength="2" name="tmaxwoche" id="tmaxwoche">
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
	// Erstellen der Einfügeanweisung in SQL
	$insertquery = "UPDATE anzahlklausurtest ";
	$insertquery .= "SET maxklausurtag = " . ($_POST['kmaxtag']) . ", maxklausurwoche = " . ($_POST['kmaxwoche']) . ", maxtesttag = " . ($_POST['tmaxtag']) . ", maxtestwoche = " . ($_POST['tmaxwoche']) . "";
	$insertquery .= " WHERE anzahlID = '1';";
	
	// Einfügen der Formulardaten in die Lehrertabelle
	$datenbank->query($insertquery);
			
	// Überprüfung ob der Datensatz angelegt wurde
	if ($datenbank->affected_rows > 0)
	{
		// Speichern des Ausgabestrings in eine Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Das Passwort wurde geändert.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Das Passwort stimmt nicht überein!</p>";
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

<hr>
<table class="ausgabe">
	<caption>Momentane Begrenzung</caption>
	<tr>
		<th>Max. Klausuren Tag</th>
		<th>Max. Klausuren Woche</th>
		<th>Max. Tests Tag</th>
		<th>Max. Tests Woche</th>
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
</html>