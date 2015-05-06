<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php
// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
$datenbank->set_charset('utf8');

if (isset($_POST['loeschefach']) && isset($_POST['loesche']))
{
	$loescheQuery = "delete from faecher where fachID = " . $_POST['loesche'];
	
	$datenbank->query($loescheQuery);
	
	if ($datenbank->affected_rows > 0)
	{
		$ausgabe = "<p class=\"erfolgreich\">Es wurde der Datensatz mit der ID " . $_POST['loesche'] . " gelöscht.</p><hr>";
	}
}

// Einfügen des neuen Fachs
if (isset($_POST['fachanlegen']))
{
	if ($_POST['fach'] != "")
	{
		$insertQuery = "INSERT INTO faecher ";
		$insertQuery .= "(name) values";
		$insertQuery .= "('" . mysql_real_escape_string(strtoupper($_POST['fach'])) . "');";
		
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

// Abfrage Bereich
$abfrageFach = "select * from faecher;";

// Ergebnisse der Abfragen
$ergebnisFach = $datenbank->query($abfrageFach);

?>

<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<fieldset>
		<legend>Fach anlegen</legend>
		<p>
			<label for="fach">Fach:</label><input type="text" id="fach" name="fach">
		</p>
		<p>
			<label>&nbsp;</label><input type="submit" value="Fach anlegen" name="fachanlegen"> <input type="reset" value="Zurücksetzen" name="zuruecksetzen">
		</p>
	</fieldset>
</form>
<hr>
<?php
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table class="ausgabe">
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
