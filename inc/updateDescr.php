<?php
require("db.php");
$q ="update sito set descrizione = '".pg_escape_string($_POST['descr'])."' where id=".$_POST['id'];
$e = pg_query($connection, $q);
if(!$e){die("Errore nella query: \n" . pg_last_error($connection));}else{echo "La descrizione è stat aggiornata correttamente"; }
?>
