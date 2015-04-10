<?php
// Einfügen der Bibliotheken
include '../include/sessionkontrolle.php';
include '../include/kalender.class.php';
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
</head>
<body>
	<div id="container">
		<?php
		include '../html_include/header.php';
		include '../html_include/navigation.php';
		?>
		<div id="content">
		
			<main>
			<?php 
			$kalender = new kalender();
			
			$kalender->baueKalender(2015, 4);
			?>
			 </main>
			
		</div>
		
		<?php
		include '../html_include/footer.php';
		?>

	</div>
</body>

</html>