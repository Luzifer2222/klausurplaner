<?php
// Titel:           globale_termine_anlegen.php
// Version:         1.0
// Autor:			PHPmeetsSQL
// Datum:           20.05.15
// Beschreibung:    Zuständig für das Einfügen/Löschen eines globalen Termins

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Einfügen eines neuen Termins
// in die Tabelle Belegtetage
if (isset($_POST['anlegen']))
{
	
	if (!isset($_POST['ganzertag'][0]))
	{
		$_POST['ganzertag'][0] = 0;
	}
	if (!isset($_POST['relevant'][0]))
	{
		$_POST['relevant'][0] = 0;
	}
		
	if (pruefedatum($_POST['beginndatum']) && pruefedatum($_POST['endedatum']))
	{
	// Überprüfung ob alle Felder des Einfügeformulars ausgefüllt wurden
		if ($_POST['nametermin'] != "" && $_POST['beginndatum'] != "" && $_POST['endedatum'] != "")
		{
			if (strtotime($_POST['beginndatum']) <= strtotime($_POST['endedatum']))
			{
				// Erstellen der Einfügeanweisung in SQL
				$insertquery = "INSERT INTO belegtetage (name, beginndatum, endedatum, ganzertag, relevant) values ";
				$insertquery .= "('" . mysqli_real_escape_string($datenbank,($_POST['nametermin'])) . "', '" . date("Y-m-d", strtotime($_POST['beginndatum'])) . "', '" . date("Y-m-d", strtotime($_POST['endedatum']));
				$insertquery .= "', '" . $_POST['ganzertag'][0] . "', '" . $_POST['relevant'][0] . "');";
				
				try
				{
					// Einfügen der Formulardaten in die Lehrertabelle
					$datenbank->query($insertquery);
				}	
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
			
				// Überprüfung ob der Datensatz angelegt wurde
				if ($datenbank->affected_rows > 0)
				{
					// Speichern der Erfolgreichen Ausgabe in der Variable
					$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde ein neuer Termin angelegt.</p>";
				}
				else
				{
					// Speichern des Fehlerstrings in eine Variable
					$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein neuer Termin angelegt.</p>";
				}
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe = "<hr><p class=\"error\">Der Terminbeginn muss vor dem Terminende liegen!</p>";
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
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Es wurde kein gültiges Datum eingegeben!</p>";
	}
}

// Überprüfung ob der Button 'Lösche' gedrückt wurde
if (isset($_POST['loeschetermin']) && isset($_POST['loesche']))
{
	// Speichern der delete Abfrage und Durchführung der Abfrage
	$terminDelete = "DELETE from belegtetage where belegtID = " . $_POST['loesche'];
	$datenbank->query($terminDelete);
	
	// Überprüfung ob der Datensatz gelöscht wurde
	if ($datenbank->affected_rows > 0)
	{
		// Speichern der Erfolgreichen Ausgabe in der Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein Datensatz gelöscht</p>";
	}
}

// Abfragen
$terminQuery = "SELECT * from belegtetage;";

// Durchgeführte Abfrage
$terminErgebnis = $datenbank->query($terminQuery);
?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="globaletermineanlegen" class="anlegen">
	<fieldset>
		<legend>Globale Termine anlegen</legend>
		<p>
			<label for="nametermin">Terminname:</label><input type="text" pattern="[A-z0-9ÄÖÜäöü .-]{2,100}" min="2" maxlength="100" id="nametermin" name="nametermin" />
		</p>
		<p>
			<label for="beginndatum">Terminbeginn:</label><input type="text" pattern="([0-9]{2}).([0-9]{2}).([0-9]{4})" id="beginndatum" name="beginndatum" value="<?php  echo date("d.m.Y", time()) ?>" />
		</p>
		<p>
			<label for="endedatum">Terminende:</label><input type="text" pattern="([0-9]{2}).([0-9]{2}).([0-9]{4})" id="endedatum" name="endedatum" value="<?php  echo date("d.m.Y", time()) ?>" />
		</p>
		<p>
			<label for="ganzertag">Ganzer Tag:</label><input type="checkbox" id="ganzertag" name="ganzertag[]" value="1" />
		</p>
		<p>
			<label for="relevant">Unterrichtsrelevant:</label><input type="checkbox" id="relevant" name="relevant[]" value="1" />
		</p>
		<p>
			<label>&nbsp;</label><input type="submit" name="anlegen" value="Termin anlegen"> <input type="reset" value="Zurücksetzen">
	
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
	<caption>Angelegte globale Termine:</caption>
		<tr>
			<th>TerminID</th>
			<th>Terminname</th>
			<th>Von/Bis Datum</th>
			<th>Ganzer Tag</th>
			<th>Unterrichtsrelevant</th>
			<th><input type="submit" value="Löschen" name="loeschetermin" /></th>
		</tr>
				<?php
				while ($daten = $terminErgebnis->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $daten->belegtID . "</td>";
					echo "<td>" . $daten->name . "</td>";
					if ($daten->beginndatum == $daten->endedatum)
					{
						echo "<td>" . date("d.m.Y", strtotime($daten->beginndatum)) . "</td>";
					}
					else
					{
						echo "<td>" . date("d.m.", strtotime($daten->beginndatum)) . "-" . date("d.m.Y", strtotime($daten->endedatum)) . "</td>";
					}
					if ($daten->ganzertag == 1)
					{
						echo "<td>Ja</td>";
					}
					else
					{
						echo "<td>Nein</td>";
					}
					if ($daten->relevant == 1)
					{
						echo "<td>Ja</td>";
					}
					else
					{
						echo "<td>Nein</td>";
					}
					echo "<td><input type=\"radio\" value=\"$daten->belegtID\" name=\"loesche\" />";
					echo "</tr>";
				}
				?>
				<tr>
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