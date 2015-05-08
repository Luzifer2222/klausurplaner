<?php
// Einfügen der Konfigurations-Dateien
require_once './config/cts.conf.php';
?>

<?php
// Einfügen der Bibliotheken
require_once './include/sessionkontrolle.class.php';
require_once './include/loginfunktion.php';
include_once './include/kalender.class.php';
include_once './include/pruefedatum.php';
?>

<?php session_start()?>
<!doctype html>
<html>
<head>
<?php include_once 'html_include/head.php';?>
</head>
<body>
	<div id="container">
		<?php
		include_once 'html_include/header.php';
		if (isset($_GET['seite']) && $_GET['seite'] == 'einausloggen.php')
			include_once './einausloggen.php';
		include_once 'html_include/navigation.php';
		?>
		<div id="content">


			<main>
			<?php
			if (isset($einausgeloggt))
				echo $einausgeloggt;
			
			if (isset($_GET['seite']))
			{
				switch ($_GET['seite'])
				{
					// Benutzerbereich
					case 'passwort_aendern.php':
						include_once 'benutzer/passwort_aendern.php';
						break;
						
					// Klassenbereich
					case 'stundenplan_anzeigen.php':
						include_once 'klassen/stundenplan_anzeigen.php';
						break;
					
					// Kalenderbereich
					case 'kalender_anzeigen.php':
						include_once 'kalender/kalender_anzeigen.php';
						break;
					case 'neuer_termin.php':
						include_once 'kalender/neuer_termin.php';
						break;
					
					// Adminbereich
					case 'globale_termine_anlegen.php':
						include_once 'admin/globale_termine_anlegen.php';
						break;
					case 'klasse_anlegen.php':
						include_once 'admin/klasse_anlegen.php';
						break;
					case 'fach_anlegen.php':
						include_once 'admin/fach_anlegen.php';
						break;
					case 'lehrer_anlegen.php':
						include_once 'admin/lehrer_anlegen.php';
						break;
					case 'abteilung_anlegen.php':
						include_once 'admin/abteilung_anlegen.php';
						break;
					case 'gewichtung_anlegen.php':
						include_once 'admin/gewichtung_anlegen.php';
						break;
					case 'stundenplan_bearbeiten.php':
						include_once 'admin/stundenplan_bearbeiten.php';
						break;
				}
			}
			
			?>
			</main>

		</div>
		
		<?php
		include_once 'html_include/footer.php';
		?>

	</div>
</body>
</html>