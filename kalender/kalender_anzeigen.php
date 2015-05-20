<?php
// Titel:           kalender_anzeigen.php
// Version:         1.0
// Autor:			PHPmeetsSQL
// Datum:           20.05.15
// Beschreibung:    Zuständig für das Anzeigen des Kalenders

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>

<?php

$schuljahresbegin = date("d.m.Y", mktime(0, 0, 0, 1, 1, date("Y", time()) - 2));
$schuljahresende = date("d.m.Y", strtotime("$schuljahresbegin +1 year"));

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Abfrage der Klasse
$klassenQuery = "SELECT klassenID, name FROM klassen;";
$klassenErgebnis = $datenbank->query($klassenQuery);

?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="formular">
	<fieldset>
		<legend>Auswahl des Schuljahres</legend>
		<p>
			<label for="schuljahr">&nbsp;</label> <select id="schuljahr" name="schuljahr">
					<?php
					
					if (!isset($_POST['schuljahr']))
					{
						$_POST['schuljahr'] = date("Y", time()) - 1;
					}
					
					for ($i = 0 ; $i < 3 ; $i++)
					{
						
						if ($_POST['schuljahr'] == date("Y", strtotime($schuljahresbegin)))
						{
							echo "<option value=\"" . date("Y", strtotime($schuljahresbegin)) . "\" selected=\"selected\" >" .
										 date("Y", strtotime($schuljahresbegin)) . "/" . date("Y", strtotime($schuljahresende)) . "</option>\n";
						}
						else
						{
							echo "<option value=\"" . date("Y", strtotime($schuljahresbegin)) . "\">" . date("Y", strtotime($schuljahresbegin)) . "/" .
										 date("Y", strtotime($schuljahresende)) . "</option>\n";
						}
						$schuljahresbegin = date("d.m.Y", strtotime("$schuljahresbegin +1 year"));
						$schuljahresende = date("d.m.Y", strtotime("$schuljahresende +1 year"));
					}
					
					?>
				</select> <label for="klassenID">&nbsp;</label> <select id="klassenID" name="klassenID">
				<option value="0">Global</option>
					<?php
					while ($daten = $klassenErgebnis->fetch_object())
					{
						if ($_POST['klassenID'] == $daten->klassenID)
						{
							echo "<option value=\"$daten->klassenID\" selected=\"selected\" >$daten->name</option>";
						}
						else
						{
							echo "<option value=\"$daten->klassenID\">$daten->name</option>";
						}
					}
					
					?>
				</select> <input type="submit" name="anzeigen" value="Anzeigen">
		</p>
	</fieldset>
</form>
<hr />

<?php
$kalender = new kalender();
$kalender->setDatabaseconnection($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

if (isset($_POST['anzeigen']))
{
	$kalender->baueKalender($_POST['schuljahr'], $_POST['klassenID']);
}
else
{
	$kalender->baueKalender(date("Y", time()) - 1);
}
?>

<hr>
<table class="ausgabe">
	<caption>Agenda:</caption>
		<tr>
		<th><p class=unrelevant>Unterrichtsunrelevanter Termin</p></th>
		<th><p class=relevant>Halbtägiger unterrichtsunrelevanter Termin</p></th>
		</tr>
		<tr>
		<th><p class=globalertermin>Halbtägiger globaler Termin</p></th>
		<th><p class=ganzglobalertermin>Globaler Termin</p></th>
		</tr>
		<tr>
		<th><p class=klausur>Klausur</p></th>
		<th><p class=test>Test</p></th>
		</tr>
</table>
<hr>