<?php
session_start();
require_once("db.php");
$insert="insert into liste.localita(id_comune, localita)values(".$_POST['comuneGid'].", '".pg_escape_string($_POST['localita'])."')";
$exec = pg_query($connection, $insert);
if(!$exec){echo "Errore: " . pg_last_error($connection);}else{echo "ok";}
?>
