<?php

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

if (isset($_POST['anlegen']))
{
	if (pruefedatum($_POST['datum']))
	{
	if ($_POST['vonstunde'] <= $_POST['bisstunde'])
	{
		if ($_POST['thema'] != "" && $_POST['datum'] != "" && $_POST['art'] != "")
		{
			echo $_POST['datum'];
			$insertquery = "INSERT INTO kalendertermine (datum, art, thema, vonstunde, bisstunde, fachID, klassenID, lehrerID) VALUES ";
			$insertquery .= "('" . date("Y-m-d", strtotime($_POST['datum'])) . "', '" . $_POST['art'][0] . "', '" . $_POST['thema'] . "', '" . $_POST['vonstunde'] . "', '" .
						 $_POST['bisstunde'] . "', '" . $_POST['fach'] . "', '" . $_POST['klasse'] . "', '" . $_SESSION['ID'] . "')";
			
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
	else
	{
		if ($_POST['art'][0] == 1)
		{
			$ausgabe = "<hr><p class=\"error\">Der Endzeitpunkt der Klausur ist vor dem Beginn. Bitte ändern!</p>";
		}
		else
		{
			$ausgabe = "<hr><p class=\"error\">Der Endzeitpunkt des Tests ist vor dem Beginn. Bitte ändern!</p>";
		}
	}
	}
	else
	{
		$ausgabe = "<hr><p class=\"error\">Es wurde kein gültiges Datum eingegeben!</p>";
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
		// Speichern der Errormeldung in die Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! es wurde kein Datensatz gelöscht</p>";
	}
}

// Abfragen
$terminQuery = "SELECT kal.terminID, kal.datum, kal.art, kal.thema, kal.vonstunde, kal.bisstunde, fach.name AS 'fachname', lehrer.nachname, klasse.name AS 'klassenname' ";
$terminQuery .= "FROM kalendertermine kal, faecher fach, lehrer, klassen klasse ";
$terminQuery .= "WHERE kal.fachID = fach.fachID ";
$terminQuery .= "AND kal.lehrerID = lehrer.lehrerID ";
$terminQuery .= "AND kal.klassenID = klasse.klassenID";
$abfrageKlasse = "SELECT klassenID, name from klassen;";
$abfrageFach = "SELECT fachID, name from faecher;";

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
			<label for="datum">Datum:</label><input type="text" pattern="([0-9]{2}).([0-9]{2}).([0-9]{4})" id="datum" name="datum" value="<?php  echo date("d.m.Y", time()) ?>" />
		</p>
		<p>
			<label for="art">Art:</label><select id="art" name="art">
				<option value="" selected="selected">Auswahl treffen</option>
				<option value="1">Klausur</option>
				<option value="0">Test</option>
			</select>
		</p>
		<p>
			<label for="thema">Thema:</label><input type="text" pattern="[A-z0-9ÄÖÜäöü]{2,50}[ -]{0,10}" min="4" maxlength="50" id="thema" name="thema">
		</p>
		<p>
			<label for="vonstunde">Beginn:</label><select id="vonstunde" name="vonstunde">
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
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table class="ausgabe">
		<tr>
			<th>Terminname</th>
			<th>Klasse</th>
			<th>Lehrer</th>
			<th>Fach</th>
			<th>Datum</th>
			<th>Von</th>
			<th>Bis</th>
			<th>Art</th>
			<th><input type="submit" value="Löschen" name="loeschetermin" /></th>
		</tr>
				<?php
				while ($daten = $terminErgebnis->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $daten->thema . "</td>";
					echo "<td>" . $daten->klassenname . "</td>";
					echo "<td>" . $daten->nachname . "</td>";
					echo "<td>" . $daten->fachname . "</td>";
					echo "<td>" . date("d.m.Y", strtotime($daten->datum)) . "</td>";
					echo "<td>" . $daten->vonstunde . ". Stunde</td>";
					echo "<td>" . $daten->bisstunde . ". Stunde</td>";
					if ($daten->art == 1)
					{
						echo "<td>Klausur</td>";
					}
					elseif ($daten->art == 0)
					{
						echo "<td>Test</td>";
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
			<td><input type="submit" value="Löschen" name="loeschetermin" /></td>
	
	</table>
</form>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>