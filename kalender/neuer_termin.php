<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
$datenbank->set_charset('utf8');

if (isset($_POST['anlegen']))
{
	
	if (!isset($_POST['art'][0]))
	{
		$_POST['art'][0] = 0;
	}
	
	if ($_POST['thema'] != "" && $_POST['datum'] != "")
	{
		$insertquery = "INSERT INTO kalendertermine (datum, art, thema, vonstunde, bisstunde, fachID, lehrerID, klassenID) VALUES ";
		$insertquery .= "('" . $_POST['datum'] . "', '" . $_POST['art'][0] . "', '" . $_POST['thema'] . "', '" . $_POST['vonstunde'] . "', '" .
					 $_POST['bisstunde'] . "', '" . $_POST['fach'] . "', '" . $_SESSION['benutzername'] . "', '" . $_POST['klasse'] . "')";
		
		try
		{
			$datenbank->query($insertquery);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		
		if ($datenbank->affected_rows > 0)
		{
			$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde 1 Datensatz angelegt.</p>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Alle Felder müssen ausgefüllt werden!</p>";
	}
}

if (isset($_POST['loeschetermin']) && isset($_POST['loesche']))
{
	$terminDelete = "DELETE from kalendertermine where terminID = " . $_POST['loesche'];
	
	$datenbank->query($terminDelete);
	
	if ($datenbank->affected_rows > 0)
	{
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Wenn der Termin nicht gelöscht wurde
		// Speichern der Error meldung in die Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! es wurde kein Datensatz gelöscht</p>";
	}
}

// Abfragen
$terminQuery = "SELECT * from kalendertermine;";
$abfrageKlasse = "SELECT * from klassen;";
$abfrageFach = "SELECT * from faecher;";

// Durchgeführte Abfrage
$terminErgebnis = $datenbank->query($terminQuery);
$ergebnisKlasse = $datenbank->query($abfrageKlasse);
$ergebnisFach = $datenbank->query($abfrageFach);
?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="terminanlegen" class="anlegen">
	<fieldset>
		<legend>Termin Anlegen</legend>
		<p>
			<label for="klasse">Klasse:</label> <select id="klasse" name="klasse">
							<?php
							while ($daten = $ergebnisKlasse->fetch_object())
							{
								echo "<option value=\"$daten->klassenID\">" . $daten->name . "</option>";
							}
							?>
						</select>
		</p>
		<p>
			<label for="fach">Fach:</label> <select id="fach" name="fach">
							<?php
							while ($daten = $ergebnisFach->fetch_object())
							{
								echo "<option value=\"$daten->fachID\">" . $daten->name . "</option>";
							}
							?>
						</select>
		</p>
		<p>
			<label for="datum">Datum:</label><input type="date" min="<?php echo date("Y-m-d", time()) ?>" id="datum" name="datum" value="<?php  echo date("Y-m-d", time()) ?>" />
		</p>
		<p>
			<label for="art">Art:</label><select id="art" name="art">
				<option value="" selected="selected">Auswahl treffen</option>
				<option value="1">Klausur</option>
				<option value="0">Test</option>
			</select>
		</p>
		<p>
			<label for="thema">Thema:</label><input type="text" id="thema" name="thema">
		</p>
		<p>
			<label for="vonstunde">Begin:</label><select id="vonstunde" name="vonstunde">
	 		<?php
				for ($i = 1 ; $i <= 12 ; $i++)
				{
					if ($i < 10)
					{
						echo "<option value=\"$i\">Anfang 0$i. Stunde</option>";
					}
					else
					{
						echo "<option value=\"$i\">Anfang $i. Stunde</option>";
					}
				}
				?>
	 		</select>
		</p>
		<p>
			<label for="bisstunde">Ende:</label><select id="bisstunde" name="bisstunde">
	 		<?php
				for ($i = 1 ; $i <= 12 ; $i++)
				{
					if ($i < 10)
					{
						echo "<option value=\"$i\">Ende 0$i. Stunde</option>";
					}
					else
					{
						echo "<option value=\"$i\">Ende $i. Stunde</option>";
					}
				}
				?>
	 		</select>
		</p>
		<p>
			<label>&nbsp;</label><input type="submit" name="anlegen" value="Termin anlegen"> <input type="reset" value="Zurücksetzen">
	
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
		<th>TerminID</th>
		<th>Terminname</th>
		<th>Klasse</th>
		<th>Lehrer</th>
		<th>Fach</th>
		<th>Datum</th>
		<th>Von</th>
		<th>Bis</th>
		<th>Klausur?</th>
		<th><form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
				<input type="submit" value="Löschen" name="loeschetermin" /></th>
	</tr>
				<?php
				while ($daten = $terminErgebnis->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $daten->terminID . "</td>";
					echo "<td>" . $daten->thema . "</td>";
					echo "<td>" . $daten->klassenID . "</td>";
					echo "<td>" . $daten->lehrerID . "</td>";
					echo "<td>" . $daten->fachID . "</td>";
					echo "<td>" . date("d.m.Y", strtotime($daten->datum)) . "</td>";
					echo "<td>" . $daten->vonstunde . "</td>";
					echo "<td>" . $daten->bisstunde . "</td>";
					if ($daten->art == 1)
					{
						echo "<td>Ja</td>";
					}
					else
					{
						echo "<td>Nein</td>";
					}
					echo "<td><input type=\"radio\" value=\"$daten->terminID\" name=\"loesche\" />";
					echo "</tr>";
				}
				?>
				<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="submit" value="Löschen" name="loeschetermin" />
			</form></td>

</table>

<?php
$datenbank->close();
?>
