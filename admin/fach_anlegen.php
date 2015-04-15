<?php
// Einfügen der Bibliotheken
$wurzelVerzeichnis = realpath($_SERVER['DOCUMENT_ROOT']);
include_once $wurzelVerzeichnis . '/include/sessionkontrolle.class.php';
include_once $wurzelVerzeichnis . '/config/cts.conf.php';
?>

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
	echo $_POST['loeschefach'];
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
		$insertQuery .= "('" . strtoupper($_POST['fach']) . "');";

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
<!Doctype html>
<html>
<head>
<?php
// Einfügen der im head-Bereich nötigen Informationen
include_once $wurzelVerzeichnis . '/html_include/head.php';
?>
</head>
<body>
	<div id="container">
		<?php
		include_once $wurzelVerzeichnis . '/html_include/header.php';
		include_once $wurzelVerzeichnis . '/html_include/navigation.php';
		?>
		<div id="content">

			<main>
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

			<table class="ausgabe">
				<tr>
					<th>FachID</th>
					<th>Fachname</th>
					<th><form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
							<input type="submit" value="Löschen" name="loeschefach" /></th>
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
					<td><input type="submit" value="Löschen" name="loeschefach" />
					</form></td>
			
			</table>
			</main>

		</div>
		
		<?php
		include_once $wurzelVerzeichnis . '/html_include/footer.php';
		?>

	</div>
</body>

</html>