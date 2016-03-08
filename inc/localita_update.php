<?php
require_once("db.php");
$a = "update liste.localita set localita = '".pg_escape_string($_POST['loc'])."' where id_localita = ".$_POST['id'];
$b = pg_query($connection,$a);
if(!$b){echo "errore: " .pg_last_error($connection);}else{echo "ok";}
?>
