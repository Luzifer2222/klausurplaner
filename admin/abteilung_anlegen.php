<?php
// test
// Titel:           abteilung_anlegen.php
// Version:         1.0
// Autor:			PHPmeetsSQL
// Datum:           20.05.15
// Beschreibung:    Zuständig für das Einfügen/Löschen einer Abteilung/Fachbereich

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$session = new sessionkontrolle();
$session->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Überprüfung ob der Button zum Anlegen einer neuen Abteilung betätigt wurde
if (isset($_POST['abtanlegen']))
{
	// Überprüfung ob das Feld des Einfügeformulars ausgefüllt wurde
	if ($_POST['abtname'] != "")
	{
		// Erstellen der Abfrage zum Erzeugen einer neuen Abteilung
		// und Ausführen der Abfrage
		$abfrageabteilung = "insert into abteilung (name) values ('" . mysqli_real_escape_string($datenbank,($_POST['abtname'])) . "');";
		$datenbank->query($abfrageabteilung);
		
		// Überprüfung ob die Abfrage erfolgreich war
		if ($datenbank->affected_rows > 0)
		{
			// Speichern der Erfolgreichen Ausgabe in der Variable
			$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde ein neuer Fachbereich angelegt.</p>";
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde keine neue Abteilung angelegt.</p>";
		}
	}
	else // Wenn kein Abteilungsname angegeben wurde
	{
		// Wenn nicht erfolgreich speichern der Errormeldung in der Variablen
		$ausgabe = "<hr><p class=\"error\">Sie müssen einen Fachbereichsnamen angeben.</p>";
	}
}

// Wenn der Button zum Löschen einer Abteilung gedrückt wurde
if (isset($_POST['loescheabt']) && isset($_POST['loesche']))
{
	// Speichern der Abfrage zum löschen einer Abteilung
	// und Ausführen der Abfrage
	$abfrageabteilung = "delete from abteilung where abteilungID = " . $_POST['loesche'] . ";";
	$datenbank->query($abfrageabteilung);
	
	// Überprüfung ob die Abfrage erfolgreich war
	if ($datenbank->affected_rows > 0)
	{
		// Speichern der Erfolgsmeldung in der Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein Datensatz gelöscht.</p>";
	}
}

// Abfrage zur Ausgabe der Tabelle Abteilung
// zur Übersicht
$datenbankAusgabe = $datenbank->query("select * from abteilung");
?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="anlegen">
	<fieldset>
		<legend>Fachbereich anlegen</legend>
		<p>
			<label>Fachbereichsname:</label><input type="text" pattern="[A-z0-9ÄÖÜäöü .-]{2,50}" min="4" maxlength="50" name="abtname" />
		</p>
		<p>
			<label>&nbsp;</label> <input type="submit" name="abtanlegen" value="Fachbereich anlegen" /> <input type="reset" value="Zurücksetzen" name="zuruecksetzen">
		</p>
	</fieldset>
</form>

<?php
// Ausgabe ob Eintrag in die Datenbank erfolgreich war.
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>

<hr>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table class="ausgabe">
		<caption>Angelegte Fachbereiche:</caption>
		<tr>
			<th>FachbereichsID</th>
			<th>Fachbereichsname</th>
			<th><input type="submit" name="loescheabt" value="Löschen?"></th>
		</tr>
    	   <?php
								while ($lehrertabelle = $datenbankAusgabe->fetch_object())
								{
									echo "<tr>";
									echo "<td>" . $lehrertabelle->abteilungID . "</td>";
									echo "<td>" . $lehrertabelle->name . "</td>";
									echo "<td><input type=\"radio\" name=loesche value=\"" . $lehrertabelle->abteilungID . "\">";
									echo "</tr>";
								}
								?>
	    <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" name="loescheabt" value="Löschen?"></td>
		</tr>
	</table>
</form>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>
