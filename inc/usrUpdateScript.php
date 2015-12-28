<?php
session_start();
require_once("db.php");
$id=$_POST['idusr'];
$nome=pg_escape_string($_POST['nome']);
$cognome=pg_escape_string($_POST['cognome']);
$classe=$_POST['classe'];
$attivo=$_POST['attivo'];

$update="update usr set nome='$nome', cognome='$cognome', attivo=$attivo, classe=$classe where id=$id";
$exec = pg_query($connection, $update);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{echo "I dati dell'utente sono stati modificati"; }
?>
