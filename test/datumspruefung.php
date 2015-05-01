<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
$datenbank->set_charset('utf8');

?>

<hr>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="formular">
	<fieldset>
		<legend>Datum-Test</legend>
		<p>
			<label for="datum">Datum: (DD.MM.YYYY)</label> <input type="text" pattern="([0-9]{2}).([0-9]{2}).([0-9]{4})" name="datum" id="datum" value="<?php  echo date("d.m.Y", time()) ?>">
		</p>
		<p>
		<input type="submit" name="speichern" value="Speichern">
		</p>
	</fieldset>
</form>
<hr>

<?php
// Überprüfung ob der Submitbutton gedrückt wurde
if (isset($_POST['speichern']))
{
 echo pruefeDatum ($_POST['datum']);
}

function pruefeDatum ($datum) 
{
	$tag = substr($datum, 0, 2);
	$monat = substr($datum, 3, 2);
	$jahr = substr($datum, 6, 4);
	$check = checkdate ( $monat, $tag, $jahr );
	if ($check)
	{
		$ausgabe = true;
		return $ausgabe;
	}
	else
	{
		$ausgabe = false;
		return $ausgabe;
	}
}
?>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>
</html>