<?php 
//Kontrolle ob User angemeldet ist und Administratorrechte hat
$session = new sessionkontrolle();
$session->AdminBereich();
?>

<?php

// Öffnen der Datenbankverbindung
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbankverbindung auf UTF8 setzen
$datenbank->set_charset('utf8');

// Überprüfung ob der Button zum Anlegen einer neuen Abteilung betätigt wurde
if (isset($_POST['abtanlegen']))
{
	if ($_POST['abtname'] != "")
	{
		// Erstellen der Abfrage zum erzeugen einer neuen Abteilung
		// und Ausführen der Abfrage
		$abfrageabteilung = "insert into abteilung (name) values ('" . mysql_real_escape_string($_POST['abtname']) . "');";
		$datenbank->query($abfrageabteilung);
		
		// Überprüfung ob die Abfrage erfolgreich war
		if ($datenbank->affected_rows > 0)
		{
			// Speichern der Erfolgreichen Ausgabe in der Variable
			$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde 1 Datensatz angelegt.</p>";
		}
		else
		{
			// Wenn nicht erfolgreich speichern der Errormeldung in der
			// Variablen
			$ausgabe = "<hr><p class=\"error\">Es ist ein Fehler aufgetreten <br />Es wurde kein Datensatz erzeugt.</p>";
		}
	}
	else // Wenn kein Abteilungsname angegeben wurde
	{
		// Wenn nicht erfolgreich speichern der Errormeldung in der Variablen
		$ausgabe = "<hr><p class=\"error\">Sie müssen einen Abteilungsnamen angeben.</p>";
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
		// Wenn die Abteilung nicht gelöscht wurde
		// Speichern der Error meldung in die Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! es wurde kein Datensatz gelöscht</p>";
	}
}

// Abfrage zur Ausgabe der Tabelle Abteilung
// zur Übersicht
$datenbankAusgabe = $datenbank->query("select * from abteilung");

?>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="anlegen">
	<fieldset>
		<legend>Abteilung anlegen</legend>
		<p>
			<label>Abteilungsname:</label><input type="text" min="4" maxlength="50" name="abtname" />
		</p>
		<p>
			<label>&nbsp;</label> <input type="submit" name="abtanlegen" value="Abteilung anlegen" /> <input type="reset" value="Zurücksetzen" name="zuruecksetzen">
		</p>
	</fieldset>
</form>

<?php
if (isset($ausgabe))
	echo $ausgabe;
?>
<hr>
<table class="ausgabe">
	<caption>Angelegte Abteilungen</caption>
	<tr>
		<th>AbteilungsID</th>
		<th>Abteilungsname</th>
		<th><form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
				<input type="submit" name="loescheabt" value="Löschen?"></th>
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
		<td><input type="submit" name="loescheabt" value="Löschen?">
			</form></td>
	</tr>
</table>



<?php
$datenbank->close();
?>