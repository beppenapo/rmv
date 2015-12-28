<?php
session_start();
require_once("db.php");
$id=$_POST['idPoi'];
$descrizione=pg_escape_string($_POST['descrizione']);

$update="update sito set descrizione='$descrizione' where id=$id";
$exec = pg_query($connection, $update);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{echo "La descrizione è stata modificata"; }
?>
