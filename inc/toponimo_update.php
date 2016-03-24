<?php
require_once("db.php");
$a = "update liste.toponimo set toponimo = '".pg_escape_string($_POST['topo'])."' where id_toponimo = ".$_POST['id'];
$b = pg_query($connection,$a);
if(!$b){echo "errore: " .pg_last_error($connection);}else{echo "ok";}
?>
