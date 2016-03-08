<?php
session_start();
require("db.php");
$a = "delete from usr where id =".$_POST['idUsr'];
$b = pg_query($connection, $a);
if($b){echo "Ok, l'utente è stato definitivamente eliminato!";}else{echo "Errore nella query: \n" . pg_last_error($connection);}
?>
