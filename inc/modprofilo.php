<?php
session_start();
require_once("db.php");
$id = $_SESSION['id_user'];
$nome=pg_escape_string($_POST['nome']);
$cognome=pg_escape_string($_POST['cognome']);
$email=pg_escape_string($_POST['email']);
$classe=$_POST['classe'];

$update="update usr set nome='$nome', cognome='$cognome', classe=$classe where id=$id";
$exec = pg_query($connection, $update);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{
 $_SESSION['nome']=$nome;
 $_SESSION['cognome']=$cognome;
 header ("Refresh: 3; URL=../index.php");
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
   <meta name="generator" content="Bluefish 2.2.5" >
   <meta name="author" content="Giuseppe Naponiello" >
   <meta name="robots" content="INDEX,FOLLOW" />
   <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
   <link href="../css/reset.css" rel="stylesheet" media="screen" />
   <link href="../css/default.css" rel="stylesheet" media="screen" />
   <link href="../css/banner.css" rel="stylesheet" media="screen" />
   <link href="../js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
<title>Rete Museale del Valdarno di sotto</title>

</head>
<body>
 <header id="head">
  <?php require_once('header.php')?>
 </header>
<section>
   <div id="descrizione">
     <h1 style='text-align: center;font-size: 1.3em; line-height: 1.5em;'>Profilo personale di <?php echo($_SESSION['nome'].' '.$_SESSION['cognome']); ?><br/>Da questa pagina potrai modificare i tuoi dati personali</h1>
   </div>
  </section>
<div id="wrap">
 <div id="colLeft">
  <h1>Utente modificato con successo<br/>Tra 3 secondi verrai reindirizzato nella home page del sito.</h1>
 </div>
</div><!-- colLeft -->
<div id="colRight">
<div id="right-nav">
<aside>
 <section id="loginWrap">
 <?php 
  if(isset($_SESSION['id_user'])){include_once('usrmenu.php'); }
  else{include_once('login_form.php');} 
 ?>
 </section>
 <section id="navLink">
  <header><h1>Link</h1></header>
  <nav class="navLink">
   <?php include_once('link.php'); ?>
  </nav>
 </section>

 <section id="navDownload">
  <header><h1>Materiale scaricabile</h1></header>
  <nav class="navLink">
   <?php include_once('download.php'); ?>
  </nav>
 </section>
</aside>
</div><!-- right-nav -->
</div><!-- colRight -->
<div style="clear:both !important;"></div>

</div><!-- wrap -->
<div id="foot">
 <footer>
  <?php require_once("footer.php"); ?>
 </footer>
</div>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/fade-plugin.js"></script>
<script type="text/javascript" src="../js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="../js/func.js"></script> 
</body>
</html>
<?php } ?>

