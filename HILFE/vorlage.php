<!-- Grundgerüst für HTML-Dokumente bitte immer dieses Verwenden!  -->

<?php
// Einfügen der Bibliotheken
include '../include/sessionkontrolle.class.php';
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
			<!-- Hier kommt Content -->
			 </main>
			
		</div>
		
		<?php
		include '../html_include/footer.php';
		?>

	</div>
</body>

</html>