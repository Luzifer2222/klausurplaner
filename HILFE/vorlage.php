<!-- Grundgerüst für HTML-Dokumente bitte immer dieses Verwenden!  -->

<?php
// Einfügen der Bibliotheken
include_once '../include/sessionkontrolle.class.php';
?>

<?php

$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>
<!Doctype html>
<html>
<head>
<?php
// Einfügen der im head-Bereich nötigen Informationen
include_once '../html_include/head.php';
?>
</head>
<body>
	<div id="container">
		<?php
		include_once '../html_include/header.php';
		include_once '../html_include/navigation.php';
		?>
		<div id="content">
		
			<main>
			<!-- Hier kommt Content -->
			 </main>
			
		</div>
		
		<?php
		include_once '../html_include/footer.php';
		?>

	</div>
</body>

</html>