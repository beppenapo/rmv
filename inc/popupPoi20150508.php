<?php
include('db.php');

$array = $_POST['arr'];
$i = count($array);
if($i==0) {$h1='Non è stata selezionata alcuna punto di interesse';}
$id = '';
for($x=0; $x<$i; $x++ ) { $id .= 's.id = '.$array[$x].' OR '; }
$items = substr($id, 0, -4);

$query=("
SELECT 
 s.id, 
 s.sito_nome as sito, 
 p.nome as provincia, 
 c.nome as comune, 
 l.localita, 
 t.toponimo, 
 m.microtoponimo, 
 dg.def_generale as def_gen, 
 ds.def_specifica as def_spec, 
 per.periodo_cultura as periodo, 
 i.nome_icone as ico,
 st_xmin(s.the_geom) as xmin, 
 st_xmax(s.the_geom) as xmax, 
 st_ymin(s.the_geom) as ymin, 
 st_ymax(s.the_geom) as ymax,
 st_X(s.the_geom) as lon,
 st_Y(s.the_geom) as lat
FROM 
 sito s, 
 province p, 
 comuni c, 
 liste.localita l, 
 liste.toponimo t, 
 liste.microtoponimo m, 
 liste.definizione_generale dg, 
 liste.definizione_specifica ds, 
 liste.periodo_cultura per, 
 liste.icone i
WHERE 
 s.id_comune = c.gid
 and s.id_localita = l.id_localita
 and s.id_toponimo = t.id_toponimo
 and s.id_microtoponimo = m.id_microtoponimo
 and c.id_provinc::text = p.id_provinc::text
 and s.id_def_generale = dg.id_def_generale
 and s.id_def_specifica = ds.id_def_specifica
 and s.id_periodo = per.id_periodo_cultura
 and dg.ico = i.id_icone
 and ($items)
ORDER BY sito asc
");
$result = pg_query($connection, $query);
$righe = pg_num_rows($result);
if(!$result){die("Errore nella query: \n" . pg_last_error($connection));}
else {
   //echo "<div id='resContentHeader'><h1>$h1</h1></div>";
   echo "<div id='resContentData'><ul class='ulpopup'>";
   if($righe != 0) {
    for ($x = 0; $x < $righe; $x++){
       $idPoi = pg_result($result, $x,"id");
       $sito = pg_result($result, $x,"sito");
       $comune = pg_result($result, $x,"comune");
       $localita = pg_result($result, $x,"localita");
       $provincia = pg_result($result, $x,"provincia");
       $toponimo = pg_result($result, $x,"toponimo");
       $microtoponimo = pg_result($result, $x,"microtoponimo");
       $def_gen = pg_result($result, $x,"def_gen"); 
       $def_spec = pg_result($result, $x,"def_spec");
       $periodo = pg_result($result, $x,"periodo");
       $nome_icone = pg_result($result, $x,"ico");
       $comune = stripslashes($comune);
       $localita = stripslashes($localita);
       $provincia = stripslashes($provincia);
       $toponimo = stripslashes($toponimo);
       $microtoponimo = stripslashes($microtoponimo);
       $def_gen = stripslashes($def_gen);
       $def_spec = stripslashes($def_spec);
       $periodo = stripslashes($periodo);
       $nome_icone = stripslashes($nome_icone);
       $inv = stripslashes($inv);
       $nctn = stripslashes($nctn);
       $sigla = stripslashes($sigla);
       $xmin = pg_result($result, $x, "xmin");
       $ymin = pg_result($result, $x, "ymin");
       $xmax = pg_result($result, $x, "xmax");
       $ymax = pg_result($result, $x, "ymax");
       $lon = pg_result($result, $x, "lon");
       $lat = pg_result($result, $x, "lat");
       $extent = $xmin.','.$ymin.','.$xmax.','.$ymax;
       
       $toponimo = ($toponimo == 'non determinabile')?'':$toponimo;
       $microtoponimo = ($microtoponimo == 'non determinabile')?'':$microtoponimo;
       $periodo = ($periodo == 'Non determinabile')?'':$periodo;
       $def_gen = ($def_gen == 'non determinabile')?'':$def_gen;
       $def_spec = ($def_spec == 'non determinabile')?'':$def_spec;
       echo "<li>
         <h2 class='trigger' id='$idPoi' ext='$extent' ll='$lon,$lat' ico='img/ico/$nome_icone' title='clicca per centrare la mappa sul punto selezionato'>$sito</h2>
         <table>
          <tr><td>Provincia: </td><td>$provincia</td></tr>
          <tr><td>Comune: </td><td>$comune</td></tr>
          <tr><td>Località: </td><td>$localita</td></tr>
          <tr><td>Toponimo: </td><td>$toponimo</td></tr>
          <tr><td>Microtoponimo: </td><td>$microtoponimo</td></tr>
          <tr><td>Definizione generale: </td><td>$def_gen</td></tr>
          <tr><td>Definizione specifica: </td><td>$def_spec</td></tr>
          <tr><td>Periodo/Cultura: </td><td>$periodo</td></tr>
          <tr><td><a href='poi.php?id=$idPoi' title='Visualizza scheda completa'>per saperne di più</a></td><td></td></tr>
         </table> 
       </li>";
    }
   }else {echo "<li><h1>L'area selezionata presenta schede in lavorazione</h1></li>";}
   echo "</ul></div>";
}
?>
<script type="text/javascript">
$('.trigger').on({
   click: function(){ 
       if($('.trigger').hasClass('active')){$('.trigger').removeClass('active');}
       $(this).addClass('active');
       
       var ll = $(this).attr('ll'); 
       if (ll == 0) return;    
       var parse = ll.split(','); 
       var newll= new OpenLayers.LonLat(parse[0],parse[1]);
       map.setCenter(newll,18);
   },
   mouseenter: function() {
       var id_poi=$(this).attr('id');
       var featHiLite = highlightLayer.getFeaturesByAttribute('id', id_poi);
       highlightLayer.drawFeature(featHiLite[0], "select");
   }, 
   mouseleave: function() { 
       var id_poi=$(this).attr('id');
       var featHiLite = highlightLayer.getFeaturesByAttribute('id', id_poi);
       highlightLayer.drawFeature(featHiLite[0], "default");
       
   }
});
</script>
