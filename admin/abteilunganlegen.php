<?php
// Starten/Wiederaufnehmen einer Session
session_start();

// Prüfen ob das Array der der Session initialisiert wurde
if (!isset($_SESSION['benutzername']) && $_SESSION['admin'] == true)
{
	// Programm abbruch, da die Session nicht initialisiert wurde.
	// Oder der Benutzer kein Administrator ist
	exit("<p>Sie haben keinen Zugang zu der Seite!<br><a href=\"/login.php\">Login Seite</a></p>");
}
?>

<html>
<head>
<title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt,
	Pascal Lawitzky</title>
<!-- Angaben zum Zeichensatz, immer UTF-8 -->
<meta charset="utf-8">
<!-- Angabe des Zeichensatzes für HTML5 -->
<meta http-eqiv="content-type" content="text/html; charset=utf-8">
<!-- Angabe des Zeichensatzes für ältere Browser -->

<!-- Angabe wie die Seite sich verhalten soll -->
<meta http-equiv="expires" content="0">
<!-- Seite wird immer von Server geholt und nicht aus dem cache geladen -->

<!-- Angabe zu den Autoren der Website und andere Informationen -->
<meta name="author"
	content="Daniel Thielking, Robin Gebhardt, Pascal Lawitzky">
<meta name="project-group" content="PHPmeetsSQL G3">
<meta name="description" content="Klausurplaner Software">

<!-- Einfügen des FavoritenIcons -->
<link rel="icon" href="../pictures/favicon/favicon.png"
	sizes="16x16 32x32" type="images/png">
<!-- Einfügen der CSS-Dateine -->
<link href="../style/style.css" rel="stylesheet" type="text/css">
    
<?php
// Einfügen des Konfigurations skripts
include '../config/cts.conf.php';

// Öffnen der Datenbankverbindung
$link = mysqli_connect($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Überprüfung ob der Button zum Anlegen einer neuen Abteilung betätigt wurde
if (isset($_POST['abtanlegen']))
{
	if ($_POST['abtname'] != "")
	{
		// Erstellen der Abfrage zum erzeugen einer neuen Abteilung
		// und Ausführen der Abfrage
		$abfrageabteilung = "insert into abteilung (name) values ('" . $_POST['abtname'] . "');";
		mysqli_query($link, $abfrageabteilung);
		
		// Überprüfung ob die Abfrage erfolgreich war
		if (mysqli_affected_rows($link) > 0)
		{
			// Speichern der Erfolgreichen Ausgabe in der Variable
			$ausgabe = "<p class=\"erfolgreich\">Es wurde 1 Datensatz angelegt.</p>";
		}
		else
		{
			// Wenn nicht erfolgreich speichern der Errormeldung in der
			// Variablen
			$ausgabe = "<p class=\"error\">Es ist ein Fehler aufgetreten <br />Es wurde kein Datensatz erzeugt.</p>";
		}
	}
	else // Wenn kein Abteilungsname angegeben wurde
	{
		// Wenn nicht erfolgreich speichern der Errormeldung in der Variablen
		$ausgabe = "<p class=\"error\">Sie müssen einen Abteilungsnamen angeben.</p>";
	}
}

// Wenn der Button zum Löschen einer Abteilung gedrückt wurde
if (isset($_POST['loescheabt']) && isset($_POST['loesche']))
{
	// Speichern der Abfrage zum löschen einer Abteilung
	// und Ausführen der Abfrage
	$abfrageabteilung = "delete from abteilung where abteilungID = " . $_POST['loesche'] . ";";
	mysqli_query($link, $abfrageabteilung);
	
	// Überprüfung ob die Abfrage erfolgreich war
	if (mysqli_affected_rows($link) > 0)
	{
		// Speichern der Erfolgsmeldung in der Variable
		$ausgabe = "<p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Wenn die Abteilung nicht gelöscht wurde
		// Speichern der Error meldung in die Variable
		$ausgabe = "<p class=\"error\">Fehler! es wurde kein Datensatz gelöscht</p>";
	}
}

// Abfrage zur Ausgabe der Tabelle Abteilung
// zur Übersicht
$datenbankAusgabe = mysqli_query($link, "select * from abteilung");

?>
</head>
<body>

	<!-- Formular zum erstellen einer neuen Abteilung -->
	<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
		<fieldset>
			<legend>Abteilung anlegen</legend>
			<p>
				<label>Abteilung: <br> <input type="text" min="4" maxlength="50"
					name="abtname" /></label>
			</p>
			<p>
				<input type="submit" name="abtanlegen" value="Abteilung anlegen" />
			</p>
		</fieldset>
	</form>
      
  <?php
		if (isset($ausgabe))
			echo $ausgabe;
		?>
      
  <table>
		<tr>
			<th>AbteilungsID</th>
			<th>Abteilungsname</th>
			<th><form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
					<input type="submit" name="loescheabt" value="Löschen?"></th>
		</tr>
       <?php
							while ($lehrertabelle = mysqli_fetch_assoc($datenbankAusgabe))
							{
								echo "<tr>";
								echo "<td>" . $lehrertabelle["abteilungID"] . "</td>";
								echo "<td>" . $lehrertabelle["name"] . "</td>";
								echo "<td><input type=\"radio\" name=loesche value=\"" . $lehrertabelle["abteilungID"] . "\">";
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

</body>
<?php
mysqli_close($link);
?>
</html>