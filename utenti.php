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
<?php require("inc/meta.php"); ?>
<link href="js/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" >
<style>
 #newUsr{width:100%;margin:10px auto;}
 #newUsr form{width:50%;margin:0px auto;}
 #newUsr input, #newUsr select{width:100% !important; margin-bottom:10px;padding: 5px; border-radius: 5px; border: 1px solid grey;}
 #newUsr table{width:90%;}
 #newUsr table td{padding:5px;}
 #mainContentWrap section {float: none;width: 100%;padding:0px !important;}
 #toggleButton{cursor:pointer;}
 select { margin: 0; background: #fff; outline: none; display: inline-block;cursor: pointer; -webkit-appearance: none; -moz-appearance: none;text-indent: 0.01px;}
</style>
</head>
<body>
<header id="head"><?php require_once('inc/head.php')?></header>

<div id="wrapMain">
  <div id="mainContent" class="wrapContent">
   <div id="mainContentWrap">
 <section id="toggleForm">
 <header id="toggleButton">Registra un nuovo utente</header>
 <div id="toggleDiv">
  <div id="newUsr">
  <form name="utente" action="utente.php" method="post" accept-charset="utf-8">

      <label>Nome:</label>
      <input type="text" name="nome" id="nome" placeholder="Inserisci nome utente" required>

      <label>Cognome:</label>
      <input type="text" name="cognome" id="cognome" placeholder="Inserisci cognome utente" required>

      <label>E-mail:</label>
      <input type="email" name="email" id="email" placeholder="Inserisci un indirizzo email valido" required>

      <label>Classe utente:</label>
      <select name="classe" id="classe" required>
       <option value='' selected disabled>selezione una classe utente</option>
       <option value='1'>Amministratore</option>
       <option value='2'>Utente semplice</option>
      </select>

      <input class="formSubmit" type="submit" value="salva" />

  </form>
 </div>
 </div>
</section>
<section id="tabella">
<header><span lang="it">Lista completa degli utenti registrati</span></header>
     <div id="filtri">
      <input type="search" placeholder="cerca utente" id="filtro">
      <i class="fa fa-undo clear-filter" title="Pulisci filtro"></i>
      <a href="#" class="export" id="csv" title="esporta dati tabella in formato csv">CSV</a>
     </div>
<table class="zebra footable toggle-arrow" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
 <thead>
  <tr class='csv'>
   <th>Utente</th>
   <th>Email</th>
   <th>Classe</th>
   <th>Attivo</th>
   <th></th>
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

      echo "<tr class='csv'>
            <td>$cognome $nome</td>
            <td>$email</td>
            <td>$classeDef</td>
            <td>$stato</td>
            <td><a href='#' data-idusr='".$id."' class='modUsr'>modifica</a></td>
            </tr>";

    }
   }
  ?>
 </tbody>
 <tfoot class="hide-if-no-paging">
       <tr>
        <td colspan="5">
         <div class="pagination pagination-centered"></div>
        </td>
       </tr>
      </tfoot>
</table>
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
</div><!-- right-nav -->
</div><!-- colRight -->
<div style="clear:both !important;"></div>

</div>
 <footer><?php require_once("inc/footer_test.php"); ?></footer>
 
 
<div class="myDialog">
    <div class="myDialogWrapContent">
        <div class="myDialogContent">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain"></div>
        </div>
    </div>
</div> 
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.sort.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.paginate.js"></script>
<script type="text/javascript" src="js/FooTable/js/footable.filter.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
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
     $(".myDialog").fadeIn('fast');
     $(".myDialogContentHeader i").click(function(){$(".myDialog").fadeOut('fast');});
    },
    error: function(richiesta,stato,errori){alert ('errore' + idUsr)},
   }); //fine ajax
 });
$('.footable').footable();
$('.clear-filter').click(function (e) {
  e.preventDefault();
  $("#filtri span").text('');
  $('.footable').trigger('footable_clear_filter');
 });
 $("#csv").click(function (event) {
   exportTableToCSV.apply(this, [$('.zebra'), 'utenti.csv']);
 }); 
});
</script>
</body>
</html>
