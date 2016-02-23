<?php
session_start();
require_once("inc/db.php");
$classe = ($_SESSION['classe'] == 1) 
  ? '<select name="classe" id="classe" required>
      <option value="1">Amministratore</option>
      <option value="2">Utente semplice</option>
     </select>'
  :'<input type="text" value="Utente semplice" disabled>
    <input type="hidden" value="2">';
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
   <meta name="generator" content="Bluefish 2.2.5" >
   <meta name="author" content="Giuseppe Naponiello" >
   <meta name="robots" content="INDEX,FOLLOW" />
   <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
   <link href="css/reset.css" rel="stylesheet" media="screen" />
   <link href="css/default.css" rel="stylesheet" media="screen" />
   <link href="css/banner.css" rel="stylesheet" media="screen" />
   <link href="css/tooltip.css" rel="stylesheet" media="screen" />
   <link href="js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
<title>Rete Museale del Valdarno di sotto</title>
<style>
 .update{float:left;width:50%;margin:0px auto;}
 /*.nth1{margin-right:85px;}*/
 .update form{width:95%;margin:0px auto;}
 .update input, .update select{width:100% !important;}
 .update table{width:90%;}
 .update table td{padding:5px;}

</style>
</head>
<body>
 <header id="head">
  <?php require_once('inc/header.php')?>
 </header>
<section>
   <div id="descrizione">
     <h1 style='text-align: center;font-size: 1.3em; line-height: 1.5em;'>Profilo personale di <?php echo($_SESSION['nome'].' '.$_SESSION['cognome']); ?><br/>Da questa pagina potrai modificare i tuoi dati personali</h1>
   </div>
  </section>
<div id="wrap">
 <div id="colLeft">
  <h2 class="red" style="margin:15px 0px;text-align:center;">Attenzione, tutti i campi modificabili sono obbligatori</h2>
  <div id="upDati" class="update nth1">
   <form name="utente" action="inc/modprofilo.php" method="post" accept-charset="utf-8">
    <table>
     <tbody>
      <tr>
       <td><label>Nome:</label></td><td><input type="text" name="nome" id="nome" value="<?php echo($_SESSION['nome']); ?>" required/></td>
      </tr>
      <tr>
       <td><label>Cognome:</label></td><td><input type="text" name="cognome" id="cognome" value="<?php echo($_SESSION['cognome']); ?>" required/></td>
      </tr>
      <tr>
       <td><label>E-mail:</label></td><td>
         <input type="email" value="<?php echo($_SESSION['email']); ?>" disabled/>
      </td>
      </tr>
      <tr>
       <td><label>Classe utente:</label></td><td><?php echo($classe);?></td>
      </tr>
      <tr>
       <td colspan="2"><span id="msgdati" class="red"></span></td>
      </tr>
      <tr>
       <td colspan="2"><input class="formSubmit" type="submit" value="modifica dati" /></td>
      </tr>
     </tbody>
    </table>
   </form>
 </div>
 
 <div id="upPwd" class="update">
  <form name="pwd" action="inc/modpwd.php" method="post" accept-charset="utf-8">
    <table>
     <tbody>
      <tr>
       <td><label>Nuova Password:</label></td>
       <td><input type="password" id="newpwd" name="newpwd" placeholder="inserisci la nuova password" required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
    oninput="setCustomValidity('')" ></td>
      </tr>
      <tr>
       <td><label>Conferma Password:</label></td>
       <td><input type="password" id="checkpwd" name="checkpwd" placeholder="ridigita la password inserita" required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
    oninput="setCustomValidity('')" ></td>
      </tr>
      <tr>
       <td colspan="2"><span id="msg2" class="red"></span></td>
      </tr>
      <tr>
       <td colspan="2"><input class="formSubmit" type="submit" value="modifica password" /></td>
      </tr>
     </tbody>
    </table>
  </form>
 </div>
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

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/fade-plugin.js"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="js/func.js"></script> 
<script type="text/javascript">
window.onload = function () {
    document.getElementById("newpwd").onchange = validatePassword;
    document.getElementById("checkpwd").onchange = validatePassword;
}

function validatePassword(){
 var pass2=document.getElementById("checkpwd").value;
 var pass1=document.getElementById("newpwd").value;
 if(pass1!=pass2) document.getElementById("checkpwd").setCustomValidity("Attenzione le password non coincidono!");
 else document.getElementById("checkpwd").setCustomValidity('');  
}

</script>
</body>
</html>
