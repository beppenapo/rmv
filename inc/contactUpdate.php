<?php
session_start();
require_once("db.php");
$id=$_POST['idPoi'];
$contatti=pg_escape_string($_POST['contatti']);

$update="update sito set contatti='$contatti' where id=$id";
$exec = pg_query($connection, $update);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{echo "I contatti sono stati modificati"; }
?>
