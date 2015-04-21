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

// Abfrage Bereich
$abfrageLehrer = "select * from lehrer;";
$abfrageFach = "select k.klassenID, k.name, l.vorname, l.nachname from klassen k, lehrer l ";
$abfrageFach .= "where k.klassenlehrerID = l.lehrerID;";

// Ergebnisse der Abfragen
$ergebnisLehrer = $datenbank->query($abfrageLehrer);
$ergebnisFach = $datenbank->query($abfrageFach);

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

?>
<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<fieldset>
		<legend>Klasse anlegen</legend>
		<p>
			<label for="klasse">Klasse</label><input type="text" id="klasse" name="klasse">
		</p>
		<p>
			<label for="klassenlehrer">Klassenlehrer</label> <select id="klassenlehrer" name="klassenlehrer">
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
<hr>
<?php
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>

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