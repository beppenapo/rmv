<?php
session_start();
require_once("db.php");
$delete = "delete from liste.localita where id_localita = ".$_POST['id'];
$exec = pg_query($connection, $delete);
if(!$exec){echo "Errore nella query: \n" . pg_last_error($connection);}else{echo "ok";}
?>
