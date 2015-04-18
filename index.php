<?php 
//Einfügen der Konfigurations-Dateien
require_once 'config/cts.conf.php';
?>

<?php 
//Einfügen der Bibliotheken
require_once 'include/sessionkontrolle.class.php';
require_once 'include/loginfunktion.php';
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
		include_once 'html_include/navigation.php';
		?>
		<div id="content">


			<main>
			<?php
			
			if (isset($_GET['seite']))
			{
				switch ($_GET['seite'])
				{
					case 'abteilung_anlegen.php':
						include_once 'admin/abteilung_anlegen.php';
						break;
					case 'fach_anlegen.php':
						include_once 'admin/fach_anlegen.php';
						break;
					case 'globalen_termin_anlegen.php':
						include_once 'admin/globalen_termin_anlegen.php';
						break;
					case 'klasse_anlegen.php':
						include_once 'admin/klasse_anlegen.php';
						break;
					case 'lehrer_anlegen.php':
						include_once 'admin/lehrer_anlegen.php';
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