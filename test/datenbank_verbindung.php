<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php include '../config/database.conf.php';?>
<title>Insert title here</title>
</head>
 <body>
        <?php
    
        $con = mysqli_connect($database_conf['host'],$database_conf['user'], $database_conf['password']);
        mysqli_select_db($con, 'klausurplaner');
        $abf = mysqli_query($con, "Select * from wochentag");
        
        while ($ausgabe = mysqli_fetch_assoc($abf)) {
            echo $ausgabe["WochenTagID"] . " " . $ausgabe["Tag"] ;
        }
        
	?>
    </body>
</html>