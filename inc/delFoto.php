<?php
session_start();
require("db.php");
$file='../foto/'.$_POST['path'];

if (is_file($file)){
    if(unlink($file)){
        $del="delete from foto_poi where id_foto = ".$_POST['foto'];
        $exec = pg_query($connection, $del);
        if(!$exec){
            die("Errore nella query: \n" . pg_last_error($connection));
        }else{
            die("La foto è stata definitivamente eliminata");
        }
    }else{
        die("Non è stato possibile eliminare il file.");
    }
}else{
    echo "Problemi nell'eliminazione del file";
}
?>
