<?php
// Einfügen der Bibliotheken
$wurzelVerzeichnis = realpath($_SERVER['DOCUMENT_ROOT']);
include_once $wurzelVerzeichnis.'/include/sessionkontrolle.class.php';
include_once $wurzelVerzeichnis.'/include/kalender.class.php';
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
		include_once $wurzelVerzeichnis.'/html_include/header.php';
		include_once $wurzelVerzeichnis.'/html_include/navigation.php';
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
		include_once $wurzelVerzeichnis.'/html_include/footer.php';
		?>

	</div>
</body>

</html>