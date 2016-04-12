<?php
session_start();
require("inc/db.php");
$query=("
SELECT distinct sito.id, comuni.id as id_comune, comuni.nome AS comune, sito.sito_nome AS poi, sito_tipo.tipo, definizione_specifica.def_specifica, periodo_cultura.periodo_cultura AS periodo, accessibilita.accessibilita, sito.data_compilazione
FROM sito, liste.localita, comuni, liste.sito_tipo, liste.definizione_specifica, liste.periodo_cultura, liste.accessibilita
WHERE sito.id_comune = comuni.gid AND sito.id_sito_tipo = sito_tipo.id_sito_tipo AND sito.id_def_specifica = definizione_specifica.id_def_specifica AND sito.id_periodo = periodo_cultura.id_periodo_cultura AND sito.id_accessibilita = accessibilita.id_accessibilita order by comune asc, data_compilazione desc, poi asc;");
$result=pg_query($connection, $query);
$row = pg_num_rows($result);
$header = (!$row) ? 'Non sono presenti punti di interesse nel database ' : 'Lista completa dei puti di interesse presenti nel database ('.$row.')';
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
<html lang="it">
<head>
<?php require("inc/meta.php"); ?>
<link href="js/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" />
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
     <header><span lang="it"><?php echo $header; ?></span></header>
     <div id="filtri">
      <input type="search" placeholder="inserisci una o più parole separate da uno spazio" id="filtro">
      <i class="fa fa-undo clear-filter" title="Pulisci filtro"></i>
      <a href="#" class="export" id="csv" title="esporta dati tabella in formato csv">CSV</a>
     </div>
     <table class="zebra footable toggle-arrow" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
      <thead>
       <tr class='csv'>
        <th data-sort-initial="true">Punto di interesse</th>
        <th data-hide="phone">Comune</th>
        <th data-hide="phone">Definizione</th>
        <th data-hide="phone">Periodo</th>
        <th data-hide="phone">Accessibilità</th>
        <th data-hide="phone" data-sort-ignore="true"></th>
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
         echo "<tr class='csv'>
                <td>$poi</td>
                <td>$comune</td>
                <td>$tipo</td>
                <td>$periodo</td>
                <td>$accessibilita</td>
                <td><a href='poi.php?id=$id' title='Visualizza scheda'><i class='fa fa-link'></i> scheda</a></td>
               </tr>";
        }
       }
      ?>
      </tbody>
      <tfoot class="hide-if-no-paging">
       <tr>
        <td colspan="6">
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
  </div>
 </div>


 <div style="clear:both !important;"></div>
</div> <!-- wrapMain -->
 <footer><?php require_once("inc/footer.php"); ?></footer>


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
 $("#poiLink").addClass('active').click(function(e){e.preventDefault();});
 $('.footable').footable();

 $('.clear-filter').click(function (e) {
  e.preventDefault();
  $("#filtri span").text('');
  $('.footable').trigger('footable_clear_filter');
 });

 $("#csv").click(function (event) {
   exportTableToCSV.apply(this, [$('.zebra'), 'catalogo.csv']);
 });

 });
</script>
</body>
</html>
