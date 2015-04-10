<?php
// Einbinden der benötigten Bibliotheken
$wurzelVerzeichniss = realpath($_SERVER['DOCUMENT_ROOT']);
include_once $wurzelVerzeichniss.'/include/sessionkontrolle.class.php';
?>

<nav>
	<ul>
		<li class="user"><a href="#">Benutzer</a></li>
		<li class="kalender"><a href="#">Kalender</a>
			<ul>
				<li><a href="/kalender/kalender_anzeigen.php">Anzeigen</a></li>
				<li><a href="/kalender/neuer_eintrag.php">Eintrag</a></li>
			</ul></li>
		<li class="admin"><a href="#">Admin</a>
			<ul>
				<li><a href="/admin/lehrer_anlegen.php">Lehrer anlegen</a></li>

				<li><a href="/admin/abteilung_anlegen.php">Abteilung anlegen</a></li>

			</ul></li>
		<li class="login"><a href="#">Login</a>
			<ul>
				<li>
					<form action="../einloggen.php" method="post" name="anmeldeformular" class="anmeldeformular">
						<fieldset>
							<legend>Anmeldeformular</legend>
							<p>
								<label for="benutzername">Benutzername:</label><input type="text" name="benutzername" id="benutzername">
							</p>
							<p>
								<label for="passwort">Passwort:</label><input type="password" name="passwort" id="passwort">
							</p>
							<input type="submit">
						</fieldset>
					</form>
				</li>
			</ul></li>
	</ul>
</nav>