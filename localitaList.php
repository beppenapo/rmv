<?php
session_start();
require_once("inc/db.php");
$query=("
SELECT localita.id_localita, localita.localita, comuni.gid as id_comune, comuni.nome as comune, count(poi.gid) as siti
FROM liste.localita
LEFT JOIN comuni ON localita.id_comune = comuni.gid
LEFT JOIN poi ON localita.id_localita = poi.id_localita
WHERE localita.id_localita != 15
GROUP BY localita.id_localita, localita.localita, comuni.id, comuni.nome
ORDER BY localita ASC
");
$result=pg_query($connection, $query);
$row = pg_num_rows($result);
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
   <meta name="generator" content="Bluefish 2.0.1" >
   <meta name="author" content="Flemmi" >
   <meta name="robots" content="INDEX,FOLLOW" />
   <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />

   <link href="css/reset.css" rel="stylesheet" media="screen" />
   <link href="css/default.css" rel="stylesheet" media="screen" />
   <link href="css/banner.css" rel="stylesheet" media="screen" />
   <link href="css/tooltip.css" rel="stylesheet" media="screen" />
   <link href="css/ico-font/css/font-awesome.css" rel="stylesheet" media="screen" />
   <link href="js/jq-ui/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet" media="screen" />
   <link href="js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
   <link rel="stylesheet" href="css/token-input.css" type="text/css" />
   <link rel="stylesheet" href="css/token-input-facebook.css" type="text/css" />
 <title>Rete Museale del Valdarno di sotto</title>
<style>
 #tabella tbody,#newLoc label{font-size:0.85em !important;}
 #tabella i{font-size:1.5em !important; padding:0px 10px;}
 /*#tabella tbody tr{cursor:pointer !important;}*/
 #tabella tbody tr:hover{background-color:#f8f8f6;}
 #tabella thead tr th:nth-child(1), #tabella thead tr th:nth-child(2){width:40%;}
 #searchContent{width:25%;float:right;padding:25px 15px 0px 0px;}
 #newLoc{width:100%;display:inline-block;margin-top:10px;}
 #newLoc label{margin-right:20px;}
 #newLoc input, #aggiorna input{width:245px;vertical-align: middle;}
 #newLoc textarea{height:15px;padding:6px;overflow:hidden;}
 #newLoc .fa{font-size:33px !important; vertical-align:top !important}
 #newLoc .fa,#newLoc .fa:hover{color:green;}
 /*********  pager tabelle **********/
 div#pager {margin:10px 0px 10px 15px; font-size:12px;float:left;width:70%;}
 div#pager table{width:80% !important;}
 table.tablesorter thead tr th, table.tablesorter tfoot tr th {min-width: 100px;}
 table#rifBiblio{border:1px solid #F5F3EC;}
 .pagesize{width:40px;appearance:menulist !important;-moz-appearance:menulist !important;-webkit-appearance:menulist !important;padding:1px !important;}
 .fa{font-size:26px !important;vertical-align:middle !important;margin-right:5px;cursor:pointer;}
 .fa:hover{color:red;}
/*************************************/
/***********  autocomplete ***********/
 .ui-menu .ui-menu-item a{background:none;font-size:0.7em !important;font-weight:normal;}
 .ui-menu .ui-menu-item a:hover{border:none !important;margin:0px !important; background-color:#d6d6d6;}
 .ui-autocomplete{z-index:1000 !important;}
/************************************/
 .submit {width: 20px !important;height: 20px;padding: 2px;background-color: #FFF;-moz-border-radius: 15px;-webkit-border-radius: 15px;border:1px solid green;}
 .submit:hover{background-color: green;color:white !important;}
 #eliminawrap,#aggiornawrap{position:fixed;top:0;left:0;width:100%;height:100%;display:none;background-color:rgba(0,0,0,0.5);text-align:center;z-index:101;}
 #elimina, #aggiorna{position:absolute;top:25%;left:30%;width:40%;height:auto;background:white;margin:0px auto;padding:30px;border:1px solid #AA191A;}
 #elimina h1, #aggiorna h1{font-size: 1.5em;margin-bottom: 20px;color: #AA191A;}
 #elimina h2{margin-bottom:20px}
 .del{width:200px;background-color:green ;border:1px solid rgb(0,73,0);cursor:pointer;color:#FFF;}
 .del:hover{background-color:rgb(0,73,0);}
 #aggiorna input{margin-bottom:30px;}
 #aggiorna label{vertical-align:top;}

</style>
</head>
<body>
 <header id="head">
  <?php require_once('inc/header.php'); ?>
 </header>

<div id="wrap">
 <div id="colLeft">
 <section>
  <h1 style="text-align:center;">Lista completa delle località presenti nel database</h1>
 </section>
 <section id="tabella">
 <div id="wrapTopTable">
  <div id="newLoc">
   <form name="newLoc" action="inc/localita_insert.php" method="post" accept-charset="utf-8">
    <label>Aggiungi una nuova Località</label>
    <input type="search" id="comune" class="comuneToken"  name="comune" data-campo="comuneGid" placeholder="Inizia a digitare il nome del Comune..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
    oninput="setCustomValidity('')"/><input type="hidden" id="comuneGid" name="comuneGid" value=""/>
    <input type="search" id="localita" name="localita" placeholder="Inizia a digitare il nome della Località..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
    oninput="setCustomValidity('')"/>
    <input type="submit" class="fa fa-plus submit" value="&#xf067;" style="font-size: 20px !important;">
   </div>
   <div id="pager" class="pager">
   <form>
    <i class="fa fa-angle-double-left first" title="Vai alla prima pagina"></i>
    <i class="fa fa-angle-left prev" title="Vai alla pagina precedente"></i>
    <i class="fa fa-angle-right next" title="Vai alla pagina successiva"></i>
    <i class="fa fa-angle-double-right last"title="Vai all'ultima pagina"></i>
    <select class="pagesize">
     <option selected="selected" value="20">20</option>
     <option value="40">40</option>
     <option value="60">60</option>
     <option value="80">80</option>
    </select>
    <span>Scegli quanti record visualizzare in ogni pagina</span><br/>
    <span class="pagedisplay"></span>
   </form>
 </div>
 <div id="searchContent">
   <form action="" id="search-form" method="post">
    <fieldset>
     <input type="search" data-column="all" id="search" name="search" placeholder="cerca..."/>
    </fieldset>
   </form>
 </div>
 <table id="dati" class="tablesorter">
  <thead>
   <tr>
    <th>Comune</th>
    <th>Località</th>
    <th>Siti presenti</th>
    <th></th>
   </tr>
  </thead>
  <tbody>
  <?php
   if($row > 0) {
    for ($x = 0; $x < $row; $x++){
      $id_comune = pg_result($result, $x,"id_comune");
      $comune = pg_result($result, $x,"comune");
      $id_localita = pg_result($result, $x,"id_localita");
      $localita = pg_result($result, $x,"localita");
      $siti = pg_result($result, $x,"siti");

      echo "<tr data-comune='".$id_comune."' data-localita='".$id_localita."' class='localitaLink'>
             <td>".$comune."</td>
             <td>".$localita."</td>
             <td>".$siti."</td>
             <td><i class='fa fa-refresh update'></i><i class='fa fa-times confirm'></i></td>
            </tr>";

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

<div id="eliminawrap">
 <div id="elimina">
  <h1>Sei davvero sicuro di voler eliminare la Località selezionata?</h1>
  <h2>Se confermi a scelta il record non potrà più essere ripristinato.</h2>
  <form name="elimina" action="inc/localita_delete.php" method="post" accept-charset="utf-8">
   <input type="hidden" name="record" id="record" value="" />
   <input type="submit" class="del delete" value="elimina" style="font-size: 20px !important;">
   <input type="button" class="del close" value="annulla" style="font-size: 20px !important;">
  </form>
 </div>
</div>

<div id="aggiornawrap">
 <div id="aggiorna">
  <h1>Aggiorna dati Località</h1>
  <form name="aggiorna" action="inc/localita_update.php" method="post" accept-charset="utf-8">
   <label>Comune: </label><input type="hidden" name="record" id="record" value="" />
   <input type="search" class="comuneToken" id="comuneUp" name="comuneUp" data-campo="comuneGidUp"  placeholder="Inizia a digitare il nome del Comune..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
    oninput="setCustomValidity('')"/><input type="hidden" id="comuneGidUp" name="comuneGidUp" value=""/>
    <br />
    <label>Località: </label><input type="search" id="localitaUp" name="localitaUp" placeholder="Inizia a digitare il nome della Località..." required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')"
    oninput="setCustomValidity('')"/>
   <input type="submit" class="del deleteUp" value="elimina" style="font-size: 20px !important;">
   <input type="button" class="del closeUp" value="annulla" style="font-size: 20px !important;">
  </form>
 </div>
</div>

<script type="text/javascript" src="js/jq-ui/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jq-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="js/tablesorter2.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.pager2.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.widgets2.js"></script>
<script type="text/javascript" src="js/jquery.tokeninput.js"></script>
<script type="text/javascript" src="js/fade-plugin.js"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="js/func.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
// $('.localitaLink').click(function(){var poi = $(this).data('localita');window.location.href='localita.php?id='+poi;});
 var pagerOptions = {
  container: $(".pager"),
  output: 'pagina: {page} - di: {totalPages} | record trovati: {filteredRows} | totale record: {totalRows}',
  removeRows: false,
  cssGoto: '.gotoPage'
 };
 $("#dati")
  .tablesorter({
   widthFixed: true, 
   widgets: ["zebra", "filter"],
   widgetOptions : {
    filter_external : '#search',
    filter_columnFilters: false,
    filter_placeholder: { search : 'Cerca...' },
    filter_saveFilters : true,
    filter_reset: '#search-submit'
   }
  })
  .tablesorterPager(pagerOptions)
 ;
$( ".comuneToken" ).autocomplete({
    source: "inc/comuneToken.php",
    minLength: 2,
    select: function( event, ui ) {
      event.preventDefault();
      //console.log(ui.item.id+'\n'+ui.item.value); return false;
      $(this).val(ui.item.value);
      var c = $(this).data("campo");
      $("#"+c).val(ui.item.id);
    }
    //,open: function(){$('.ui-menu .ui-menu-item a').removeClass('ui-corner-all');}
  });
  
  $(".confirm").click(function(){
   var idLoc = $(this).closest('tr').data('localita');
   $("#record").val(idLoc);
   $("#eliminawrap").fadeIn();
  });
  $(".close").click(function(){$("#eliminawrap").fadeOut();});
  $(".update").click(function(){
   //var idLoc = $(this).closest('tr').data('localita');
   //$("#record").val(idLoc);
   $("#aggiornawrap").fadeIn();
  });
  $(".closeUp").click(function(){$("#aggiornawrap").fadeOut();});
});
</script>
</body>
</html>
