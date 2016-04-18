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
&& in_array($extension, $allowedExts)){
    if ($_FILES["file"]["error"] > 0){
        $msg = "Errore nel caricamento: " . $_FILES["file"]["error"] . "<br>";
    }else{
        if (file_exists("foto/" . $_FILES["file"]["name"])){
            $msg = $_FILES["file"]["name"] . " esiste già un file con questo nome. ";
        }else{
            $file = pg_escape_string($_FILES["file"]["name"]);
            require("inc/db.php");
            $up=("insert into foto_poi(id_poi, percorso_foto, descr_foto)values($id, '$file', '$descrizione');");
            $exec = pg_query($connection, $up);
            if(!$exec){
                $msg = "errore nella query, foto non salvata:". pg_last_error($connection);
            }else{
                if(move_uploaded_file($_FILES["file"]["tmp_name"], "foto/" . $_FILES["file"]["name"])){
                    $msg = "Immagina caricata!<br/>Entro 5 secondi verrai reindirizzato nella pagina del punto di interesse...<br/>Se la pagina impiega troppo tempo <a href='poi.php?id=".$id."'>clicca qui</a> per tornare alla pagina precedente";
                }else{
                    $msg = "L'immagine non è stata caricata sul server";
                }
            }
        }
    }
}else{
    echo "File non valido o non selezionato!";
}
header("Refresh: 5; URL=../poi.php?id=".$id);
?>
<!DOCTYPE html>
<html lang="it">
<head>
<?php require("inc/meta.php"); ?>
<style>
 #mainContentWrap {margin:0px !important;}
 #mainContentWrap section{float:none; width:100%;}
</style>
</head>
<body>
 <header id="head"><?php require_once('inc/head.php'); ?></header>
 <div id="wrapMain">
  <div id="mainContent" class="wrapContent">
   <div id="mainContentWrap">
    <section>
        <p style="font-weight:bold;text-align:center;"><?php echo $msg; ?></p>
   </section>
  </div>
  <div id="nav">
   <aside>
    <section id="loginWrap">
     <?php
      if(isset($_SESSION['id_user'])){include_once('inc/usrmenu.php'); }
      else{include_once('inc/login_form.php');}
     ?>
    </section>
    <section id="navLink">
     <header><h1><i class="fa fa-link"></i> Link</h1></header>
     <nav class="navLink"><?php include_once('inc/link.php'); ?></nav>
    </section>
   </aside>
  </div>
 </div>


 <div style="clear:both !important;"></div>
</div> <!-- wrapMain -->
 <footer><?php require_once("inc/footer.php"); ?></footer>


<script src="js/jquery.js" type="text/javascript" ></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script src="js/func.js" type="text/javascript" ></script>
</body>
</html>
