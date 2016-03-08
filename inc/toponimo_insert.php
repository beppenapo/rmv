<?php
session_start();
require_once("db.php");
$insert="insert into liste.toponimo(id_comune, id_localita, toponimo)values(".$_POST['comuneGid'].", ".$_POST['localita'].", '".pg_escape_string($_POST['toponimo'])."')";
$exec = pg_query($connection, $insert);
if(!$exec){echo "Errore: " . pg_last_error($connection);}else{echo "ok";}
?>
