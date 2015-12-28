<?php
include('db.php');

$array = $_POST['arr'];
$i = count($array);
if($i==0) {
 echo '<h1 style="text-align:center;margin:20px 0px;">Non è stato selezionato alcun punto di interesse</h1>';
}else{
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
 if(!$result){ 
  die("Errore nella query: \n" . pg_last_error($connection));
 }else{
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
       
       //$toponimo = ($toponimo == 'non determinabile')?'':$toponimo;
       //$microtoponimo = ($microtoponimo == 'non determinabile')?'':$microtoponimo;
       //$periodo = ($periodo == 'Non determinabile')?'':$periodo;
       //$def_gen = ($def_gen == 'non determinabile')?'':$def_gen;
       //$def_spec = ($def_spec == 'non determinabile')?'':$def_spec;
       
       $fotoquery = ("select * from foto_poi where id_poi = $idPoi order by id_foto asc;");
       $fotoexec = pg_query ($connection, $fotoquery);
       $fotorow = pg_num_rows($fotoexec);
       
       echo "
        <li>
         <h2 class='active'  ico='img/ico/$nome_icone' title='clicca per centrare la mappa sul punto selezionato'>$sito</h2>
         <span> Provincia: </span><span>$provincia</span>
         <span>Comune: </span><span>$comune</span>
         <span>Località: </span><span>$localita</span>";
         if($toponimo != 'non determinabile'){echo "<span>Toponimo: </span><span>$toponimo</span>";}
         if($microtoponimo != 'non determinabile'){echo "<span>Microtoponimo: </span><span>$microtoponimo</span>";}
         if($def_gen != 'non determinabile'){echo "<span>Definizione generale: </span><span>$def_gen</span>";}
         if($def_spec != 'non determinabile'){echo "<span>Definizione specifica: </span><span>$def_spec</span>";}
         if($periodo != 'Non determinabile'){echo "<span>Periodo/Cultura: </span><span>$periodo</span>";}
         echo "<span></span><span class='linkButton'><a href='poi.php?id=$idPoi' title='Visualizza scheda completa'><i class='fa fa-file-text-o'></i> scheda</a><a href='#' title='zoom sulla mappa' class='trigger' data-id='$idPoi' ext='$extent' ll='$lon,$lat'><i class='fa fa-globe'></i> zoom</a></span>";
       if($fotorow > 0){
        echo "<div class='fotoList' data-foto='".$fotorow."'>";
        echo "<ul>";
        while ($foto = pg_fetch_assoc($fotoexec)){
         echo "<li><img class='foto'src='foto/".$foto['percorso_foto']."' title='".$foto['descr_foto']."' alt='".$foto['descr_foto']."'></li>";
        }
        echo "</ul>";
        echo "</div>";
       }
        
       
       echo "</li>";
    }
   }else{
    echo "<li><h1>L'area selezionata presenta schede in lavorazione</h1></li>";
   }
   echo "</ul></div>";
  }
 }
?>
<script type="text/javascript">
$(document).ready( function() {
 getImageSizes();
});
function getImageSizes() {
  var l = 0;
  $(".fotoList").each(function(){
  var w;
   $(this).find('img').each(function(){
    var $this = $(this);
    w = $this.height();
    l += w;
   });
   //console.log("img lungh: "+l);
   $(this).find('ul').css("width", l+60+"px");
  });
}




</script>
