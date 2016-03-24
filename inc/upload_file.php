<?php
ob_start();
$id = $_POST['poiFoto'];
$descrizione = pg_escape_string($_POST['descrFoto']);
$allowedExts = array("gif", "jpeg", "jpg", "JPG", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 1073741824)//1gb in bytes
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0){echo "Errore nel caricamento: " . $_FILES["file"]["error"] . "<br>";
  }else{
    if (file_exists("../foto/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " esiste già un file con questo nome. ";
      }
    else
      {
       $file = $_FILES["file"]["name"];
       require("db.php");
       $up=("insert into foto_poi(id_poi, percorso_foto, descr_foto)values($id, '$file', '$descrizione');");
       $exec = pg_query($connection, $up);
       if($exec) {move_uploaded_file($_FILES["file"]["tmp_name"], "../foto/" . $_FILES["file"]["name"]);}
       echo "Immagina caricata!<br/>";
       echo "Entro 5 secondi verrai reindirizzato nella pagina del punto di interesse...<br/>";
       echo "Se la pagina impiega troppo tempo <a href='../poi.php?id=".$id."'>clicca qui</a> per tornare alla pagina precedente";
      }
    }
  }
else
  {
  echo "File non valido o non selezionato!";
  }
header("Refresh: 3; URL=../poi.php?id=".$id);
?>
