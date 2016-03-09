<?php
require_once("db.php");
$a = "update liste.microtoponimo set microtoponimo = '".pg_escape_string($_POST['micro'])."' where id_microtoponimo = ".$_POST['id'];
$b = pg_query($connection,$a);
if(!$b){echo "errore: " .pg_last_error($connection);}else{echo "ok";}
?>
