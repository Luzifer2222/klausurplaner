<?php
// Einfügen der Bibliotheken
$wurzelVerzeichnis = realpath($_SERVER['DOCUMENT_ROOT']);
include_once $wurzelVerzeichnis . '/config/cts.conf.php';
include_once $wurzelVerzeichnis . '/include/loginfunktion.php';
include_once $wurzelVerzeichnis . '/include/sessionkontrolle.class.php';

?>

<?php
// Starten/Wiederaufnehmen einer Session
session_start();

$angemeldet = false;

if (isset($_POST['anmelden']))
{
	// Überprüfung ob der Benutzername von der navigation.php kommt
	if (isset($_POST['benutzername']))
	{
		// Öffnen der SQL-Datenbank zur Überprüfung der Anmeldedaten
		$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
		$datenbank->set_charset('utf8');
		
		// Erstellen der Abfrage um das Passwort des
		// Benutzers aus der Datenbank zu holen
		$passwortabfrage = "SELECT passwort, administrator from lehrer where benutzername like '" . $_POST['benutzername'] . "'";
		$abfrageerg = $datenbank->query($passwortabfrage);
		// Extrahieren der Daten aus der Abfrage
		$queryErgebnis = $abfrageerg->fetch_object();
		
		// Überprüfung ob die Abfrage erfolgreich war
		if ($abfrageerg->num_rows == 1)
		{
			// Passwort Überprüfung
			if ($queryErgebnis->passwort == verschluesselLogin($_POST['passwort']))
			{
				// Setzen des Sessionarray mit dem Benutzernamen der eingegeben wurde
				$_SESSION['benutzername'] = $_POST['benutzername'];
				$_SESSION['administrator'] = $queryErgebnis->administrator;
				$angemeldet=true;
			}
		}
	}
	
	// Prüfen ob das Array der der Session initialisiert wurde
	if (!isset($_SESSION['benutzername']))
	{
		// Programm abbruch, da die Session nicht initialisiert wurde.
		exit("<p style=\"font-weight: bold; color: #FF0000\">Benutzername oder Passwort falsch!<br />
            Sie haben keinen Zugang zu der Seite! <br /><a href=index.php>Hauptseite</a>");
	}
	
	$datenbank->close();
}

if (isset($_POST['abmelden']))
{
	$session = new sessionkontrolle();
	
	$session->ausloggen();
	
	$angemeldet=false;
}
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
			<?php 
				if($angemeldet)
				{	
					echo "<p>Sie sind nun eingeloggt!</p>";
				}
				else
				{
					echo "<p>Sie sind nun ausgeloggt!</p>";
				}
			?>
			</main>

		</div>
		
		<?php
		include_once $wurzelVerzeichnis . '/html_include/footer.php';
		?>

	</div>
</body>

</html>