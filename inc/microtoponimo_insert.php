<?php
session_start();
require_once("db.php");
$insert="insert into liste.microtoponimo(id_toponimo, microtoponimo)values(".$_POST['toponimo'].", '".pg_escape_string($_POST['microtoponimo'])."')";
$exec = pg_query($connection, $insert);
if(!$exec){echo "Errore: " . pg_last_error($connection);}else{echo "ok";}
?>
