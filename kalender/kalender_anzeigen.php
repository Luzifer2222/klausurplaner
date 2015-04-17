<?php
// Einfügen der Bibliotheken
$wurzelVerzeichnis = realpath($_SERVER['DOCUMENT_ROOT']);
include_once $wurzelVerzeichnis . '/include/sessionkontrolle.class.php';
include_once $wurzelVerzeichnis . '/include/kalender.class.php';
?>

<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>

<html>
<head>
<?php
// Einfügen der im head-Bereich nötigen Informationen
include '../html_include/head.php';
?>

<?php

$schuljahresbegin = date("d.m.Y", mktime(0, 0, 0, 1, 1, date("Y", time()) - 2));
$schuljahresende = date("d.m.Y", strtotime("$schuljahresbegin +1 year"));

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
			<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
				<fieldset>
					<legend>Auswahl des Schuljahrs</legend>
					<label for="schuljahr"></label> <select id="schuljahr" name="schuljahr">
					<?php
					
					for ($i = 0 ; $i < 5 ; $i++)
					{
						echo "<option value=\"" . date("Y", strtotime($schuljahresbegin)) . "\">" . date("Y", strtotime($schuljahresbegin)) . "/" .
									 date("Y", strtotime($schuljahresende)) . "</option>";
						$schuljahresbegin = date("d.m.Y", strtotime("$schuljahresbegin +1 year"));
						$schuljahresende = date("d.m.Y", strtotime("$schuljahresende +1 year"));
					}
					
					?>
				</select> 
				<input type="submit" name="anzeigen" value="Anzeigen">
				</fieldset>
			</form>
			<?php
			$kalender = new kalender();
			
			if (isset($_POST['anzeigen']))
			{
				$kalender->baueKalender($_POST['schuljahr']);
			}
			else
			{
				$kalender->baueKalender(date("Y", time()));
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