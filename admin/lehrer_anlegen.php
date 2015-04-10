<?php
// Einfügen der Bibliotheken
include '../include/sessionkontrolle.php';
include '../include/loginfunktion.php';
include '../config/cts.conf.php';
?>

<?php

$pruefeAdmin = new sessionkontrolle();
$pruefeAdmin->AdminBereich();

?>

<html>
<head>
<?php
// Einfügen der im head-Bereich nötigen Informationen
include '../html_include/head.php';
?>
    
<?php
// Verbindung zu Datenbank herstellen
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Einfügen eines neuen Lehrers
// in die Lehrer Tabelle
if (isset($_POST['benanlegen']))
{
	// Überprüfung ob alle Felder des Einfüge Formulars ausgefüllt wurden
	if ($_POST['vname'] != "" && $_POST['nname'] != "" && $_POST['kuerzel'] != "" && $_POST['bname'] != "" && $_POST['pwd'] != "")
	{
		
		// Erstellen der Einfüge anweisung in SQL
		$insertquery = "insert into lehrer ";
		$insertquery .= "(vorname, nachname, kuerzel, benutzername, passwort, administrator, abteilungID) values";
		$insertquery .= "('" . $_POST['vname'] . "', '" . $_POST['nname'] . "', '" . $_POST['kuerzel'] . "', '" . $_POST['bname'] . "', '";
		$insertquery .= verschluesselLogin($_POST['pwd']) . "', '" . $_POST['administrator'][0] . "', '" . $_POST['abteilung'] . "');";
		
		// Einfügen der Formulardaten in die Lehrertabelle
		$datenbank->query($insertquery);
		
		// Überprüfung ob der Datensatz angelegt wurde
		if ($datenbank->affected_rows > 0)
		{
			// Speichern des Ausgabestrings in eine Variable
			$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde 1 Datensatz angelegt.</p>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Alle Felder müssen ausgefüllt werden!</p>";
	}
}

// Überprüfung ob der Button 'Lösche' gelöscht gedrückt wurde
if (isset($_POST['loeschelehrer']) && isset($_POST['loesche']))
{
	
	// Speichern der delete Abfrage und durchführung der Abfrage
	$abfloeschelehrer = "delete from lehrer where lehrerID = " . $_POST['loesche'] . ";";
	$datenbank->query($abfloeschelehrer);
	
	//
	if ($datenbank->affected_rows > 0)
	{
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
}

// Einfache Abfragen für das Extrahieren der Abteilungen und der Lehrer
$abfrageabteilung = "select * from abteilung;";
$abfragelehrer = "select l.lehrerID, l.vorname, l.nachname, l.kuerzel, l.benutzername, l.administrator, a.name ";
$abfragelehrer .= "from lehrer l, abteilung a ";
$abfragelehrer .= "where l.abteilungID = a.abteilungID";

// Ergebnis der Abfrage aus $abfragelehrer
$ergabfragerlehrer = $datenbank->query($abfragelehrer);
?>

</head>
<body>
	<div id="container">
		<?php
		include '../html_include/header.php';
		include '../html_include/navigation.php';
		?>
		<div id="content">

			<main>
			<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post" name="lehrereinfuegen" class="lehrereinfuegen">
				<fieldset>
					<legend>Einfügen des Lehrpersonals in das 'CTS'</legend>
					<p>
						<label for="vorname">Vorname:</label> <input type="text" maxlength="50" name="vname" id="vorname">
					</p>
					<p>
						<label for="nachname">Nachname:</label> <input type="text" maxlength="50" name="nname" id="nachname">
					</p>
					<p>
						<label for="kuerzel">Kürzel:</label> <input type="text" min="4" maxlength="5" name="kuerzel" id="kuerzel">
					</p>
					<p>
						<label for="abteilung">Abteilung:</label> <select name="abteilung" id="abteilung">
							<?php
							
							$ergabteilungdb = $datenbank->query($abfrageabteilung);
							while ($daten = $ergabteilungdb->fetch_object())
							{
								echo "<option value=$daten->abteilungID>" . $daten->name . "</option>";
							}
							
							?>
        </select>
					</p>
					<p>
						<label for="administrator">Administrator:</label> <input type="checkbox" name="administrator[]" value="1">
					</p>
					<p>
						<label for="benutzername">Benutzername:</label> <input type="text" min="4" maxlength="15" name="bname">
					</p>
					<p>
						<label for="passwort">Passwort:</label> <input type="password" min="5" name="pwd">
					</p>
					<p class="button">
						<input type="submit" name="benanlegen" value="Lehrer anlegen"> <input type="reset" name="reset" value="Zurücksetzen">
					</p>
				</fieldset>
			</form>
			
  
  <?php
		// Ausgabe ob eintrag in die Datenbank erfolgreich war.
		if (isset($ausgabe))
		{
			echo $ausgabe;
		}
		?>
  <hr>
			<table class="ausgabe">
				<caption>Angelegte Lehrer</caption>
				<tr>
					<th>LehrerID</th>
					<th>Vorname</th>
					<th>Nachname</th>
					<th>Kürzel</th>
					<th>Benutzername</th>
					<th>Abteilung</th>
					<th>Admin</th>
					<th>
						<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
							<input type="submit" name="loeschelehrer" value="Löschen?">
					</th>
				</tr>
    <?php
				while ($lehrertabelle = $ergabfragerlehrer->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $lehrertabelle->lehrerID . "</td>";
					echo "<td>" . $lehrertabelle->vorname . "</td>";
					echo "<td>" . $lehrertabelle->nachname . "</td>";
					echo "<td>" . $lehrertabelle->kuerzel . "</td>";
					echo "<td>" . $lehrertabelle->benutzername . "</td>";
					echo "<td>" . $lehrertabelle->name . "</td>";
					if ($lehrertabelle->administrator == 1)
					{
						echo "<td>Ja</td>";
					}
					else
					{
						echo "<td>Nein</td>";
					}
					echo "<td><input type=\"radio\" name=\"loesche\" value=\"" . $lehrertabelle->lehrerID . "\"></td>";
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
					<td><input type="submit" name="loeschelehrer" value="Löschen?">
						</form></td>
				</tr>
			</table>
			</main>
		</div>
		<footer>Copyright &copy; 2015 Daniel Thielking, Robin Gebhardt, Pascal Lawitzky</footer>

	</div>
</body>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>
</html>