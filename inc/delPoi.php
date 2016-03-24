<?php
session_start();
require_once("db.php");
$a = "select id_foto, percorso_foto from foto_poi where id_poi = ".$_POST['id']." order by percorso_foto asc;";
$b = pg_query($connection, $a);
$foto = array();
while($f = pg_fetch_array($b)){
  array_push($foto,$f['percorso_foto']);
}
$c="delete from sito where id=".$_POST['id'];
$d = pg_query($connection, $c);
if(!$d){$msg = "Errore: " . pg_last_error($connection);}
else{
  foreach ($foto as $value) { unlink("../foto/".$value);}
  $msg = "Ok il record e i file associati sono stati definitivamente eliminati!";
}
echo $msg;
?>
