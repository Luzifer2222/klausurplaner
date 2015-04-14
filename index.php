<?php 
// Einfügen der Bibliotheken
$wurzelVerzeichnis = realpath($_SERVER['DOCUMENT_ROOT']);
?>
<!doctype html>
<html>
<head>
<?php include_once $wurzelVerzeichnis.'/html_include/head.php';?>
</head>
<body>
	<div id="container">
		<?php
		include_once $wurzelVerzeichnis.'/html_include/header.php';
		include_once $wurzelVerzeichnis.'/html_include/navigation.php';
		?>
		<div id="content">


			<main>
			
			</main>

		</div>
		
		<?php
		include_once $wurzelVerzeichnis.'/html_include/footer.php';
		?>

	</div>
</body>
</html>