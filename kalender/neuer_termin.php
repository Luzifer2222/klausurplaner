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

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Einfügen eines neuen Termins
// in die Tabelle Kalendertermine
if (isset($_POST['anlegen']))
{
	if (pruefedatum($_POST['datum']))
	{
	if ($_POST['vonstunde'] <= $_POST['bisstunde'])
	{
		// Überprüfung ob alle Felder des Einfügeformulars ausgefüllt wurden
		if ($_POST['thema'] != "" && $_POST['datum'] != "" && $_POST['art'] != "")
		{
			// Einfache Abfragen
			$fragetermin = "select datum, vonstunde, bisstunde ";
			$fragetermin .= "from kalendertermine ";
			$fragetermin .= "where klassenID = '" . $_POST['klasse'] . "';";
			$frageplan = "select wochentag, stunde ";
			$frageplan .= "from stunden ";
			$frageplan .= "where klassenID = '" . $_POST['klasse'] . "';";
			$fragegewichtung = "select * from anzahlklausurtest;";
			$frageglobal = "select beginndatum, endedatum, ganztertag, relevant ";
			$frageglobal .= "from belegtetage ";
			// $frageglobal .= "where ";	Zeitraum schon filtern?

			// Ergebnis der Abfragen
			$ergebnistermin = $datenbank->query($fragetermin);
			$ergebnisplan = $datenbank->query($frageplan);
			$ergebnisgewichtung = $datenbank->query($fragegewichtung);
			$ergebnisglobal = $datenbank->query($frageglobal);
			
			// Prüfen, ob zu der Zeit schon eine Klausur oder Test ist
			$termincheck = false;
			while ($daten = $ergebnistermin->fetch_object())
			{
				$tdatum = $daten->datum;
				$tvon = $daten->vonstunde;
				$tbis = $daten->bisstunde;
				if ($tdatum == date("Y-m-d", strtotime($_POST['datum'])))
				{
					if ($tvon < $_POST['bisstunde'] && $tbis < $_POST['vonstunde'])
					{
						$termincheck = true;
					}
				}
				else
				{
					$termincheck = true;
				}
			}
			
			// Prüfen, ob die Klasse zu der gewünschten Zeit Unterricht hat
			$eingabetag = date("N", strtotime($_POST['datum']));
			$pstunden = array();
			$plancheck = false;
			while ($daten = $ergebnisplan->fetch_object())
			{
				$ptag = $daten->wochentag;
				$pstunde = $daten->stunde;
				if ($ptag == $eingabetag)
				{
					$pstunden[] = $pstunde;
				}
			}
			if (!empty($pstunden))
			{
				reset($pstunden);
				$pvon = current($pstunden);
				end($pstunden);
				$pbis = current($pstunden);
				if ($_POST['vonstunde'] >= $pvon && $_POST['bisstunde'] <= $pbis)
				{
					$plancheck = true;
				}
			}
			/*			
			// Prüfen, ob die Gewichtung überschritten wird
			// Man muss prüfen, ob die Gewichtung für den Tag überschritten wird und danach für die Woche
			while ($daten = $ergebnisgewichtung->fetch_object())
			{
				$kmaxtag = $daten->kmaxtag;
				$kmaxwoche = $daten->kmaxwoche;
				$tmaxtag = $daten->tmaxtag;
				$tmaxwoche = $daten->tmaxwoche;
				if ()
				{
					
				}
			}
			*/	
			
			/*			
			// Prüfen, ob zu dieser Zeit ein globaler Termin (Zeitraum) vorhanden ist
			while ($daten = $ergebnisglobal->fetch_object())
			{
				$beginn = $daten->beginndatum;
				$ende = $daten->endedatum;
				$gtag = $daten->ganztertag;
				$grelevant = $daten->relevant;
				if ()
				{
					
				}
			}
			*/
			
			// Einfügen des Termins, wenn alle Anforderungen erfüllt sind
			if ($plancheck == true && $termincheck == true) // $globalcheck == true && $gewichtcheck == true)
			{
				// Erstellen der Einfügeanweisung in SQL
				$insertquery = "INSERT INTO kalendertermine (datum, art, thema, vonstunde, bisstunde, fachID, klassenID, lehrerID) VALUES ";
				$insertquery .= "('" . date("Y-m-d", strtotime($_POST['datum'])) . "', '" . $_POST['art'][0] . "', '" . $_POST['thema'] . "', '" . $_POST['vonstunde'] . "', '" .
				$_POST['bisstunde'] . "', '" . $_POST['fach'] . "', '" . $_POST['klasse'] . "', '" . $_SESSION['ID'] . "')";
			
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
					$ausgabe = "<hr><p class=\"error\">Es ist ein Fehler aufgetreten <br />Es wurde kein Datensatz erzeugt.</p>";
				}
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
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Der Endzeitpunkt der Klausur ist vor dem Beginn. Bitte ändern!</p>";
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Der Endzeitpunkt des Tests ist vor dem Beginn. Bitte ändern!</p>";
		}
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
	$terminDelete = "DELETE from kalendertermine where terminID = " . $_POST['loesche'];
	$datenbank->query($terminDelete);
	
	// Überprüfung ob der Datensatz gelöscht wurde
	if ($datenbank->affected_rows > 0)
	{
		// Speichern der erfolgreichen Ausgabe in der Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein Datensatz gelöscht</p>";
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
// Ausgabe ob Eintrag in die Datenbank erfolgreich war.
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>

<hr>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table class="ausgabe">
	<caption>Angelegte Termine:</caption>
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