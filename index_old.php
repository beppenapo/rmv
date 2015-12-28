<?php
session_start();
require_once("inc/db.php");
$poi="SELECT sito.id, sito.sito_nome, localita.localita, p.periodo_cultura as periodo, sito.data_compilazione as data, icone.nome_icone as ico
FROM sito, liste.localita,liste.icone, liste.periodo_cultura as p
WHERE sito.id_localita = localita.id_localita AND sito.id_icone = icone.id_icone AND sito.id_periodo = p.id_periodo_cultura
ORDER BY data_compilazione DESC
LIMIT 5;";
$poiexec = pg_query($connection, $poi);
$poirow = pg_num_rows($poiexec);

?>
<!DOCTYPE html>
<html lang="it">
<head>
   <meta charset="utf-8" />
   <meta name="generator" content="gedit" >
   <meta name="author" content="Giuseppe Naponiello" >
   <meta name="robots" content="INDEX,FOLLOW" />
   <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
   <link href="css/reset.css" rel="stylesheet" media="screen" />
   <link href="css/default.css" rel="stylesheet" media="screen" />
   <link href="css/banner.css" rel="stylesheet" media="screen" />
   <link href="css/tooltip.css" rel="stylesheet" media="screen" />

<title>Rete Museale del Valdarno di sotto</title>
<style>
</style>
</head>
<body>
 <header id="head">
  <?php require_once('inc/header.php')?>
 </header>

  <section id="intro">
   <article><?php require_once('inc/intro.php')?></article>
  </section>
<div id="wrap">
 <div id="colLeft">
  <div id="colLeft1">
   <div id="poi">
    <section>
     <h1 lang="it">Utimi 5 punti di interesse inseriti</h1>
     <article>
      <ul>
      <?php
        if($poirow != 0) {
         for ($x = 0; $x < $poirow; $x++){
           $idPoi = pg_result($poiexec, $x,"id"); 	
           $sitoNome = pg_result($poiexec, $x,"sito_nome");
           $localita = pg_result($poiexec, $x,"localita");
           $periodo = pg_result($poiexec, $x,"periodo");
           $data = pg_result($poiexec, $x,"data");
           $ico = pg_result($poiexec, $x,"ico");
           $sitoNome = stripslashes($sitoNome);
           $localita = stripslashes($localita);
           $periodo = stripslashes($periodo);
           echo "<li><img src='img/ico/$ico' /><a href='poi.php?id=$idPoi' title='località: $localita \nperiodo: $periodo\nClicca per aprire la scheda completa.'>$sitoNome</a></li>";
         }
        }
      ?>
       <li id="poiLinkMappa"><a href="mappa.php" title="apri la mappa">Apri la mappa per visualizzare tutti i punti</a></li>
      </ul>
     </article>
    </section>
   </div>
  </div><!--colLeft1 -->
  <div id="colLeft2">
   <div id="download">
    <section>
     <h1 lang="it">Materiale scaricabile</h1>
     <article>
     
     </article>
    </section>
   </div>
 </div><!--colLeft2 -->
</div><!-- colLeft -->
<div id="colRight">
<div id="right-nav">
<aside>
 <section id="loginWrap">
 <?php 
  if(isset($_SESSION['id_user'])){include_once('inc/usrmenu.php'); }
  else{include_once('inc/login_form.php');} 
 ?>
 </section>

 <section id="navLink">
  <header><h1>Link</h1></header>
  <nav class="navLink">
   <?php include_once('inc/link.php'); ?>
  </nav>
 </section>

 <section id="navDownload">
  <header><h1 lang="it">Materiale scaricabile</h1></header>
  <nav class="navLink">
   <?php include_once('inc/download.php'); ?>
  </nav>
 </section>
</aside>
</div><!-- right-nav -->
</div><!-- colRight -->
<div style="clear:both !important;"></div>

</div><!-- wrap -->
<div id="foot">
 <footer>
  <?php require_once("inc/footer.php"); ?>
 </footer>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/fade-plugin.js"></script>
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
  $(".effectContainer").fadeTransition({pauseTime: 6000, transitionTime: 1000, delayStart: 6000, pauseOnMouseOver: true, createNavButtons: true});
  });
</script>
</body>
</html>
