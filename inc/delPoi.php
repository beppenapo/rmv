<?php
session_start();
require_once("db.php");
$id=$_POST['idPoi'];
$descrizione=pg_escape_string($_POST['descrizione']);

$del="delete from sito where id=$id";
$exec = pg_query($connection, $del);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{echo "Il record è stato eliminato!"; }
?>
