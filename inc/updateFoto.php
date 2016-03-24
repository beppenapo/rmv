<?php
session_start();
require("db.php");
$a="update foto_poi set descr_foto = '".pg_escape_string($_POST['didascalia'])."' where id_foto = ".$_POST['foto'];
$b=pg_query($connection,$a);
if(!$b){
    die("Errore nella query: \n" . pg_last_error($connection));
}else{
    echo "I metadati della foto sono stati aggiornati"; 
}
?>
