<?php
session_start();
require("inc/db.php");
$query=("SELECT * FROM usr order by cognome asc, nome asc;");
$result=pg_query($connection, $query);
$row = pg_num_rows($result);

?>
<!DOCTYPE html>
<html>
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
   <link href="js/jq-ui/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet" media="screen" />
   <link href="js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
<title>Rete Museale del Valdarno di sotto</title>
<style>
 #newUsr{width:100%;margin:10px auto;}
 #newUsr form{width:50%;margin:0px auto;}
 #newUsr input, #newUsr select{width:100% !important;}
 #newUsr table{width:90%;}
 #newUsr table td{padding:5px;}
</style>
</head>
<body>
<header id="head"><?php require_once('inc/header.php')?></header>

<div id="wrap">
 <div id="colLeft">
 <section id="toggleForm">
 <h1 id="toggleButton">Registra un nuovo utente</h1>
 <div id="toggleDiv">
  <div id="newUsr">
  <form name="utente" action="utente.php" method="post" accept-charset="utf-8">

      <label>Nome:</label>
      <input type="text" name="nome" id="nome" required/>

      <label>Cognome:</label>
      <input type="text" name="cognome" id="cognome" required/>

      <label>E-mail:</label>
      <input type="email" name="email" id="email" required/>

      <label>Classe utente:</label>
      <select name="classe" id="classe" required>
       <option value=''>--- Classe utente ---</option>
       <option value='1'>Amministratore</option>
       <option value='2'>Utente semplice</option>
      </select>

      <input class="formSubmit" type="submit" value="salva" />

  </form>
 </div>
 </div>
</section>
<section id="tabella">
<table>
 <thead>
  <tr>
   <th>Utente</th><th>Email</th><th>Classe</th><th>Attivo</th><th></th>
  </tr>
 </thead>
 <tbody>
  <?php 
   if($row > 0) {
    for ($x = 0; $x < $row; $x++){
      $id = pg_result($result, $x,"id");
      $nome = pg_result($result, $x,"nome");
      $cognome = pg_result($result, $x,"cognome");
      $email = pg_result($result, $x,"email");
      $classe = pg_result($result, $x,"classe");
      $attivo = pg_result($result, $x,"attivo");
      
      $classeDef=($classe==1)?'Amministratore':'Utente semplice';
      $stato=($attivo==1)?'Attivo':'Non attivo';

      echo "<tr data-colore='".$attivo."'><td>$cognome $nome</td><td>$email</td><td>$classeDef</td><td>$stato</td><td><a href='#' data-idusr='".$id."' class='modUsr'>modifica</a></td></tr>";

    }
   }
  ?>
 </tbody>
</table>
</section>
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
  <header><h1>Materiale scaricabile</h1></header>
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

<script type="text/javascript" src="js/jq-ui/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jq-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="js/func.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
 $('.modUsr').click(function(){
   var idUsr = $(this).data('idusr');
   $.ajax({
    url: 'inc/usrUpdate.php',
    type: 'POST', 
    data: {idUsr : idUsr }, 
    success: function(data){
     $("<div class='dialog'>" + data + "</div>").dialog({
       title: "Utilizza il form per aggiornare i dati dell'utente selezionato.",
       height: 'auto',
       width: 500,
       resizable: false    
     }); // dialog;
    },//success
    error: function(richiesta,stato,errori){alert ('errore' + idUsr)},
   }); //fine ajax
 });

 $("#tabella table tbody tr").each(function(index){
  	var colore = $(this).data('colore');
  	if(colore == 2){$(this).css('background-color','#C4FC6F');}
  })
});
</script>
</body>
</html>
