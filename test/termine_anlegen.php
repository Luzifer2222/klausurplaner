<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="globalenterminanlegen" class="anlegen">
	<fieldset>
		<legend>Globalen Termin Anlegen</legend>
		<p>
			<label for="datum">Datum</label><input type="date" min="<?php echo date("Y-m-d", time()) ?>" id="datum" name="datum" value="<?php  echo date("Y-m-d", time()) ?>"/>
		</p>
		<p>
			<label for="art">Art</label><select id="art" name="art">
				<option value="" selected="selected">Auswahl treffen</option>
				<option value="1">Klausur</option>
				<option value="0">Test</option>
			</select>
		</p>
		<p>
			<label for="thema">Thema</label><input type="text" id="thema" name="thema">
	 	</p>
	 	<p>
	 		<label for="vonstunde">Begin</label><select id="vonstunde" name="vonstunde">
	 		<?php 
	 			for ($i = 1 ; $i <= 12 ; $i++)
	 			{
	 				if ($i < 10)
	 				{
	 					echo "<option value=\"$i\">Anfang 0$i. Stunde</option>";
	 				}
	 				else
	 				{
	 					echo "<option value=\"$i\">Anfang $i. Stunde</option>";
	 				}
	 			}
	 		?>
	 		</select>
	 	</p>
	 	<p>
	 		<label for="bisstunde">Ende</label><select id="bisstunde" name="bisstunde">
	 		<?php 
	 			for ($i = 1 ; $i <= 12 ; $i++)
	 			{
	 				if ($i < 10)
	 				{
	 					echo "<option value=\"$i\">Ende 0$i. Stunde</option>";
	 				}
	 				else
	 				{
	 					echo "<option value=\"$i\">Ende $i. Stunde</option>";
	 				}
	 			}
	 		?>
	 		</select>
	 	</p>
	</fieldset>
</form>