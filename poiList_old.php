<?php
session_start();
require_once("inc/db.php");
$query=("
SELECT distinct sito.id, comuni.id as id_comune, comuni.nome AS comune, sito.sito_nome AS poi, sito_tipo.tipo, definizione_specifica.def_specifica, periodo_cultura.periodo_cultura AS periodo, accessibilita.accessibilita, sito.data_compilazione
FROM sito, liste.localita, comuni, liste.sito_tipo, liste.definizione_specifica, liste.periodo_cultura, liste.accessibilita
WHERE sito.id_comune = comuni.gid AND sito.id_sito_tipo = sito_tipo.id_sito_tipo AND sito.id_def_specifica = definizione_specifica.id_def_specifica AND sito.id_periodo = periodo_cultura.id_periodo_cultura AND sito.id_accessibilita = accessibilita.id_accessibilita order by comune asc, data_compilazione desc, poi asc;");
$result=pg_query($connection, $query);
$row = pg_num_rows($result);

//filtro comune
$q2=("SELECT distinct c.gid, c.nome AS comune FROM comuni c, sito where sito.id_comune = c.gid order by comune asc;");
$res2=pg_query($connection, $q2);
$row2 = pg_num_rows($res2);

//filtro definizione generale
$q3=("SELECT distinct l.id_def_generale, l.def_generale from liste.definizione_generale l, sito where sito.id_def_generale = l.id_def_generale order by l.def_generale asc");
$res3=pg_query($connection, $q3);
$row3 = pg_num_rows($res3);

//filtro periodo
$q4=("SELECT distinct p.id_periodo_cultura, p.periodo_cultura from liste.periodo_cultura p, sito where sito.id_periodo = p.id_periodo_cultura order by id_periodo_cultura asc");
$res4=pg_query($connection, $q4);
$row4 = pg_num_rows($res4);

//filtro accessibilità
$q5=("SELECT distinct a.id_accessibilita, a.accessibilita from liste.accessibilita a, sito where sito.id_accessibilita = a.id_accessibilita order by accessibilita asc;");
$res5=pg_query($connection, $q5);
$row5= pg_num_rows($res5);
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
   <link href="js/jq-ui/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet" media="screen" />
   <link href="js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
<title>Rete Museale del Valdarno di sotto</title>
<style>
 #tabella tbody{font-size:0.85em;}
 #tabella tbody tr{cursor:pointer !important;}
 #tabella tbody tr:hover{background-color:yellow;}
 #tabella thead tr th:nth-child(1){width:21%;}
 #tabella thead tr th:nth-child(2){width:33%;}
 #legenda {font-size:0.8em;clear:both;}
 #fts,#filtri{float:left;width:91%;}
 #poiSearch{width:95% !important;margin-bottom:5px;}
 #filtri{margin-bottom:10px;}
 #filtri select{width:22.13%}
 #ftsButt{width:64px !important;height:64px !important;}
 .s{color:gray;font-size:0px;display:none;}

.arrow_box {
	position: relative;
background: #d1d5ad;
border: 2px solid #a5a889;
border-radius: 5px;
width: 85%;
margin: -53px auto 0px;
padding: 5px;
font-size: 0.8em;
text-align: center;
}
.arrow_box:after, .arrow_box:before {
	top: 100%;
	left: 50%;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
}

.arrow_box:after {
	border-color: rgba(209, 213, 173, 0);
	border-top-color: #d1d5ad;
	border-width: 5px;
	margin-left: -5px;
}
.arrow_box:before {
	border-color: rgba(165, 168, 137, 0);
	border-top-color: #a5a889;
	border-width: 8px;
	margin-left: -8px;
}

</style>
</head>
<body>
 <header id="head">
  <?php require_once('inc/header.php')?>
 </header>
<!--<section id="descrizione"><h1 style='text-align: center;font-size: 1.3em; line-height: 1.5em;'>Pagina di gestione degli utenti</h1></section>-->

<div id="wrap">
 <div id="colLeft">
 <section>
  <h1>Lista completa dei punti di interesse presenti nel database</h1>
 </section>
 <section id="tabella">
 <div id="wrapTopTable">
  <div id="fts">
   <input type="text" id="poiSearch" name="poiSearch" placeholder="inserisci una o più parole separate da uno spazio" >
  </div>
  <div id="filtri">
   <select name="comuneSearch">
    <option class="s" value="">filtra comune</option>
    <option value="0">Tutti i Comuni</option>
    <?php
     if($row2 > 0) {
      for ($x2 = 0; $x2 < $row2; $x2++){
       $idComune = pg_result($res2, $x2,"gid");
       $selComune = pg_result($res2, $x2,"comune");
       echo "<option value='".$idComune."'>".$selComune."</option>";
      }
     }
    ?>
   </select>
   <select name="definizioneSearch">
    <option class="s" value="">filtra definizione</option>
    <option value="0">Tutte le definizioni</option>
    <?php
     if($row3 > 0) {
      for ($x3 = 0; $x3 < $row3; $x3++){
       $idDefGen = pg_result($res3, $x3,"id_def_generale");
       $defGen = pg_result($res3, $x3,"def_generale");
       echo "<option value='".$idDefGen."'>".$defGen."</option>";
      }
     }
    ?>
   </select>
   <select name="cronoSearch">
    <option class="s" value="">filtra periodo</option>
    <option value="0">Tutti i periodi</option>
    <?php
     if($row4 > 0) {
      for ($x4 = 0; $x4 < $row4; $x4++){
       $idPeriodo = pg_result($res4, $x4,"id_periodo_cultura");
       $periodo = pg_result($res4, $x4,"periodo_cultura");
       echo "<option value='".$idPeriodo."'>".$periodo."</option>";
      }
     }
    ?>
   </select>
   <select name="accesSearch">
    <option class="s" value="">filtra accessibilità</option>
    <option value="0">Tutti i tipi</option>
    <?php
     if($row5 > 0) {
      for ($x5 = 0; $x5 < $row5; $x5++){
       $idAcc = pg_result($res5, $x5,"id_accessibilita");
       $acc = pg_result($res5, $x5,"accessibilita");
       echo "<option value='".$idAcc."'>".$acc."</option>";
      }
     }
    ?>
   </select>
  </div>
  <div id="buttSearch"><button id="ftsButt"></button></div>
  <div id='legenda'></div>
 </div>
 <table class="zebra">
  <thead>
   <tr>
    <th>Comune</th><th>Nome POI</th><th>Definizione</th><th>Periodo</th><th>Accessibilità</th><th></th>
   </tr>
  </thead>
  <tbody>
  <?php
   if($row > 0) {
    for ($x = 0; $x < $row; $x++){
      $id = pg_result($result, $x,"id");
      $id_comune = pg_result($result, $x,"id_comune");
      $comune = pg_result($result, $x,"comune");
      $poi = pg_result($result, $x,"poi");
      $tipo = pg_result($result, $x,"def_specifica");
      $periodo = pg_result($result, $x,"periodo");
      $accessibilita = pg_result($result, $x,"accessibilita");

      $accessibilita = ($accessibilita == 'non determinabile')?'':$accessibilita;
      $tipo = ($tipo == 'non determinabile')?'':$tipo;      
      $periodo = ($periodo == 'Non determinabile')?'':$periodo;

      echo "<tr data-poi='".$id."' data-comune='".$id_comune."'class='poiLink'><td>$comune</td><td>$poi</td><td>$tipo</td><td>$periodo</td><td>$accessibilita</td></tr>";

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
<script type="text/javascript" src="js/fade-plugin.js"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="js/func.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
 $('.poiLink').click(function(){
   var poi = $(this).data('poi');
   window.location.href='poi.php?id='+poi;
 });
 var righe = $('.zebra tbody tr:visible').length;
 $('#legenda').html('Il database contiene <b>'+righe+'</b> punti di interesse.');

$('.arrow_box').hide();
$('#poiSearch').focus(function(){$('.arrow_box').fadeIn('fast');}).blur(function(){$('.arrow_box').fadeOut('fast');});

 $('#ftsButt').click(function(){
  var ftsText = $('#poiSearch').val();
  var comSearch = $('select[name="comuneSearch"]').val() ;
  var defSearch = $('select[name="definizioneSearch"]').val();
  var cronoSearch = $('select[name="cronoSearch"]').val();
  var accSearch = $('select[name="accesSearch"]').val();
  var ftsWhere='';
  if(!ftsText && !comSearch && !defSearch && !cronoSearch && !accSearch){
    $('#poiSearch').addClass('errorInput');
    $('#legenda').html('Devi inserire almeno un termine di ricerca o impostare almeno un filtro!');
  }else{
    $('#legenda').html('ok!');
    if(ftsText){
     $('#poiSearch').removeClass('errorInput');
     ftsWhere = "to_tsvector('italian', sito.sito_nome ||' '||sito.descrizione ||' '||sito.note) @@ to_tsquery('";
     ftsText = ftsText.replace(/\s+/g, " & ");
     ftsWhere += ftsText+"') and ";
    }
    if(comSearch){
      if(comSearch==0){ftsWhere += "sito.id_comune > 0 and ";}else{ftsWhere += "sito.id_comune ="+comSearch+" and ";}
    }else{ftsWhere += "sito.id_comune > 0 and ";}
    
    if(defSearch){
      if(defSearch==0){ftsWhere += "sito.id_def_generale > 0 and ";}else{ftsWhere += "sito.id_def_generale ="+defSearch+" and ";}    
    }else{ftsWhere += "sito.id_def_generale > 0 and ";}

    if(cronoSearch){
      if(cronoSearch==0){ftsWhere += "sito.id_periodo > 0 and ";}else{ftsWhere += "sito.id_periodo ="+cronoSearch+" and ";}
    }else{ftsWhere += "sito.id_periodo > 0 and ";}

    if(accSearch){
      if(accSearch==0){ftsWhere += "sito.id_accessibilita > 0";}else{ftsWhere += "sito.id_accessibilita ="+accSearch;}
    }else{ftsWhere += "sito.id_accessibilita > 0";}

    console.log('fts: '+ftsWhere);

    $.ajax({
     type: "POST",
     url: "inc/ftsSearch.php",
     data: {q:ftsWhere},
     cache: true,
     success: function(html){
       $(".zebra tbody").html(html);
       var righe = $('.zebra tbody tr:visible').length;
       $('#legenda').html('<b>'+righe+'</b>  schede trovate');
       $('.poiLink').click(function(){
        var poi = $(this).data('poi');
        window.location.href='poi.php?id='+poi;
       });
     } 
   });
  }
 });

});
</script>
</body>
</html>
