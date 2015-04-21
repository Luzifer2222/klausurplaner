<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>

<?php

$schuljahresbegin = date("d.m.Y", mktime(0, 0, 0, 1, 1, date("Y", time()) - 2));
$schuljahresende = date("d.m.Y", strtotime("$schuljahresbegin +1 year"));

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
$datenbank->set_charset('utf8');
?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="formular">
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
				</select> <input type="submit" name="anzeigen" value="Anzeigen">
	</fieldset>
</form>
<hr />
<?php
$kalender = new kalender();
$kalender->setDatabaseconnection($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

if (isset($_POST['anzeigen']))
{
	$kalender->baueKalender($_POST['schuljahr']);
}
else
{
	$kalender->baueKalender(date("Y",time())-1);
}
?>
