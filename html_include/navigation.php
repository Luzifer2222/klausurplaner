<ul>
	<li class="user"><a href="#">Benutzer</a></li>
	<li class="kalender"><a href="#">Kalender</a>
		<ul>
			<li><a href="/kalender/kalenderanzeigen.php">Anzeigen</a></li>
			<li><a href="/kalender/neuereintrag.php">Eintrag</a></li>
		</ul></li>
	<li class="admin"><a href="#">Admin</a>
		<ul>
			<li><a href="/admin/lehreranlegen.php">Lehrer anlegen</a></li>

			<li><a href="/admin/abteilunganlegen.php">Abteilung anlegen</a></li>

		</ul></li>

	<li class="login"><a href="#">Login</a>
		<ul>
			<form action="../einloggen.php" method="post" name="anmeldeformular">
				<fieldset>
					<legend>Anmeldeformular</legend>
					<p>
						<label for="benutzername">Benutzername<input type="text" name="benutzername"></label>
					</p>
					<p>
						<label for="passwort">Passwort<input type="password" name="passwort"></label>
					</p>
					<input type="submit">
				</fieldset>
			</form>
		</ul></li>
</ul>