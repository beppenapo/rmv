<?php
session_start();
require("inc/db.php");
$id=$_GET['id'];
$poi="
 SELECT 
  sito.id
, sito.inv
, sito.sito
, sito.posizione
, sito.descrizione
, sito.crono_iniziale
, sito.crono_finale
, sito.note
, sito.funzionario
, sito.data_compilazione
, sito.id_accessibilita
, sito.link
, nullif(accessibilita.accessibilita, 'non determinabile') as accessibilita
, sito.id_def_generale
, nullif(definizione_generale.def_generale, 'non determinabile') as def_generale
, sito.id_def_specifica
, nullif(definizione_specifica.def_specifica, 'non determinabile') as def_specifica
, sito.id_materiale
, nullif(materiale.materiale, 'non determinabile') as materiale
, sito.id_localita
, nullif(localita.localita, 'non determinabile') as localita
, sito.id_toponimo
, nullif(toponimo.toponimo, 'non determinabile') as toponimo
, sito.id_microtoponimo
, nullif(microtoponimo.microtoponimo, 'non determinabile') as microtoponimo
, sito.id_sito_tipo
, nullif(sito_tipo.tipo, 'non determinabile') as tipo
, sito.id_stato_conservazione
, nullif(stato_conservazione.stato_conservazione, 'non determinabile') as stato_conservazione
, sito.id_tecnica
, nullif(tecnica.tecnica, 'non determinabile') as tecnica
, sito.sito_nome
, sito.id_comune
, comuni.nome AS comune
, province.nome AS provincia
, sito.id_periodo
, nullif(periodo_cultura.periodo_cultura, 'Non determinabile') as periodo_cultura
, sito.id_compilatore
, usr.cognome ||' '|| usr.nome as compilatore
, sito.contatti
, st_x(st_transform(sito.the_geom, 4326)) AS lon
, st_y(st_transform(sito.the_geom, 4326)) AS lat
FROM sito, liste.accessibilita, liste.definizione_generale, liste.definizione_specifica, liste.materiale, liste.microtoponimo, liste.sito_tipo, liste.stato_conservazione, liste.tecnica, liste.toponimo, liste.localita, comuni, province, liste.periodo_cultura, usr
WHERE sito.id_accessibilita = accessibilita.id_accessibilita 
  AND sito.id_def_generale = definizione_generale.id_def_generale 
  AND sito.id_def_specifica = definizione_specifica.id_def_specifica 
  AND sito.id_materiale = materiale.id_materiale 
  AND sito.id_microtoponimo = microtoponimo.id_microtoponimo 
  AND sito.id_sito_tipo = sito_tipo.id_sito_tipo 
  AND sito.id_stato_conservazione = stato_conservazione.id_stato_conservazione 
  AND sito.id_tecnica = tecnica.id_tecnica 
  AND sito.id_toponimo = toponimo.id_toponimo 
  AND sito.id_localita = localita.id_localita 
  AND sito.id_periodo = periodo_cultura.id_periodo_cultura 
  AND sito.id_comune = comuni.gid AND comuni.id_provinc::text = province.id_provinc::text 
  AND sito.id_compilatore = usr.id 
  AND sito.id = $id";
$poiexec = pg_query($connection, $poi);
$arr = pg_fetch_array($poiexec, 0, PGSQL_ASSOC);
$row = pg_num_rows($poiexec);

$id_comune = $arr['id_comune'];
$id_localita = $arr['id_localita'];
$id_toponimo = $arr['id_toponimo'];
$id_microtoponimo = $arr['id_microtoponimo'];
$id_periodo = $arr['id_periodo'];
$id_accessibilita = $arr['id_accessibilita'];
$id_conservazione = $arr['id_stato_conservazione'];
$id_defGen = $arr['id_def_generale'];
$id_defSpec = $arr['id_def_specifica'];
$id_tecnica = $arr['id_tecnica'];
$id_materiale = $arr['id_materiale'];
$lon = substr($arr['lon'],0,5);
$lat = substr($arr['lat'],0,5);
$h1 = $arr['sito_nome'].' (lonlat: '.$lon.', '.$lat.')';
if(isset($arr['inv']) && isset($arr['nctn'])){$inv = $arr['inv'].' / '.$arr['nctn'];}
if(!isset($arr['inv']) && !isset($arr['nctn'])){$inv = '';}
if(isset($arr['inv'])){$inv = $arr['inv'];}
if(isset($arr['nctn'])){$inv = $arr['nctn'];}

?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8" />
<meta name="generator" content="gedit" >
<meta name="author" content="Giuseppe Naponiello" >
<meta name="robots" content="INDEX,FOLLOW" />
<meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="css/responsive.css" rel="stylesheet" media="screen" />
<link href="css/print.css" rel="stylesheet" media="print" />
<link href="js/flexslider/flexslider.css" rel="stylesheet" media="screen" />

<title>Rete Museale del Valdarno di sotto</title>

<style>
 #map{position:relative;display:block;width:100%;height:400px;}
 #mapPrint{position:relative;width:700pt;height:400px;float:left;margin-left:-1000px !important;}
 .mapTitle {position: absolute;top: 0px;left: 0px;width: 90%; margin: 0px auto; padding: 1% 5%; background-color: rgba(17,17,17,0.5);  z-index: 3000;
}
 .mapTitle h1 {font-size: 1.3em; color: #fff; text-align: center;}
 .olControlZoom { top: 60px !important;}
 .olControlZoom a { height: 2em !important; width: 2em !important; line-height: 2em !important;background-color: #0A0062 !important;}
 .olControlZoom a:hover{background:#A42929 !important;}
 .olControlZoomIn { border-radius: 4px 4px 0 0;}
 .olControlZoomOut { border-radius: 0px !important;}
 .olControlZoom #max{border-radius: 0 0 4px 4px;} 
 #mainContentWrap section { display:block; width: 100%;padding:0px !important;}
 #mainContentWrap header#descrizione {color: #0A0062;border-bottom: 1px solid #0A0062;}
 #mainContentWrap header#galleria {color: #A42929;border-bottom: 1px solid #A42929;}
 #mainContentWrap header#info {color: #4e9a06;border-bottom: 1px solid #4e9a06;}
 #mainContentWrap header { font-size: 1.8em; margin: 10px 0px 20px;}
 #mainContentWrap article{line-height:1.8em;height:auto !important;}
 #mainContentWrap i{font-size:0.8em;}
 
 #galleryDiv{display:none;position:absolute;left:0;right:0;height:100%;background-color:rgba(0,0,0,0.7);}
 #galleryWrap{position:relative;margin:2% auto 0;}
 #galleryWrap header{position:relative;width:99%;background:#fff;margin:0px;padding:0.5%;text-align:right;}
 #caption{position: relative;display: inline-block;background: #fff;width: 98%;padding: 1%;}
</style>
</head>
<body onload="init()">
 <header id="head"><?php require_once('inc/head.php')?></header>
<div id="wrapMain">
 <div id="map">
  <div class="mapTitle"><h1><?php echo($h1); ?></h1></div>
 </div>
 
 <div id="mapPrint">
  <div class="mapTitle"><h1><?php echo($h1); ?></h1></div>
 </div>

 <div id="mainContent" class="wrapContent">
  <div id="mainContentWrap">
  <?php if(isset($_SESSION['id_user'])){?>
   <a href="#" id="modDescr" class="button"><i class="fa fa-file-pdf-o fa-fw"></i> modifica descrizione</a>
   <a href="#" id="modInfo" class="button"><i class="fa fa-file-pdf-o fa-fw"></i> modifica info</a>
   <a href="#" id="modFoto" class="button"><i class="fa fa-file-pdf-o fa-fw"></i> aggiungi foto</a>
  <?php } ?>
   <a href="#" id="print" class="button"><i class="fa fa-file-pdf-o fa-fw"></i> Stampa</a>
   <section> 
    <header id="descrizione"><span lang="it"><i class="fa fa-file-text-o"></i> Descrizione</span></header>
    <article>
     <?php 
        echo(nl2br($arr['descrizione'])); 
        if(isset($_SESSION['id_user'])){
      ?>
      <div class='wrapModLink'><a href="#" class="modLink" id="modDescr"> modifica descrizione</a></div>
      <?php } ?>
    </article>
   </section>
   <?php 
    $imgq = ("select * from foto_poi where id_poi = $id;");
    $imgexec = pg_query($connection, $imgq);
    $imgrow = pg_num_rows($imgexec);
    if($imgrow > 0){
   ?>
   <section>
    <header id="galleria"><span lang="it"><i class="fa fa-picture-o"></i> Galleria fotografica </span></header>
    <article>
     <div id="photoContent">
     <?php 
       while($foto = pg_fetch_array($imgexec, NULL, PGSQL_ASSOC)){
        echo "<div class='wrapThumb cursor'><img src='foto/".$foto['percorso_foto']."' alt='".$foto['descr_foto']."' title='".$foto['descr_foto']."' class='thumb' data-id='".$foto['id_foto']."'></div>";
       }
     ?>
     </div>
     <?php } ?>
     <br style="clear:both" />
    </article>
   </section>
   <section> 
    <header id="info"><span lang="it"><i class="fa fa-thumb-tack"></i> Info</span></header>
    <article id="infoContent">
    <?php 
    if(isset($_SESSION['id_user'])){
     if($inv){?><span class="key">Inv./Nctn : </span><span class="value"><?php echo($inv); ?></span><hr><?php }
    }
     if($arr['provincia']){?><span class="key">Provincia: </span><span class="value"><?php echo($arr['provincia']); ?></span><hr><?php }
     if($arr['comune']){?><span class="key">Comune: </span><span class="value"><?php echo($arr['comune']); ?></span><hr><?php }
     if($arr['id_localita'] != 15){?><span class="key">Località: </span><span class="value"><?php echo($arr['localita']); ?></span><hr><?php }
     if($arr['id_toponimo'] != 12){?><span class="key">Toponimo: </span><span class="value"><?php echo($arr['toponimo']); ?></span><hr><?php }
     if($arr['id_microtoponimo'] != 1){?><span class="key">Microtoponimo: </span><span class="value"><?php echo($arr['microtoponimo']); ?></span><hr><?php }
     if($arr['posizione']){?><span class="key">Posizione: </span><span class="value"><?php echo($arr['posizione']); ?></span><hr><?php }
     if($arr['accessibilita']){?><span class="key">Accessibilità: </span><span class="value"><?php echo($arr['accessibilita']); ?></span><hr><?php }
    if(isset($_SESSION['id_user'])){
     if($arr['tipo']){?><span class="key">Tipo: </span><span class="value"><?php echo($arr['tipo']); ?></span><hr><?php }
    }
     if($arr['def_generale']){?><span class="key">Definizione generale: </span><span class="value"><?php echo($arr['def_generale']); ?></span><hr><?php }
     if($arr['def_specifica']){?><span class="key">Definizione specifica: </span><span class="value"><?php echo($arr['def_specifica']); ?></span><hr><?php }
     if($arr['periodo']){?><span class="key">Periodo/Cultura: </span><span class="value"><?php echo($arr['periodo']); ?></span><hr><?php }
     if($arr['crono_iniziale']){?><span class="key">Cronologia iniziale: </span><span class="value"><?php echo($arr['crono_iniziale']); ?></span><hr><?php }
     if($arr['crono_finale']){?><span class="key">Cronologia finale: </span><span class="value"><?php echo($arr['crono_finale']); ?></span><hr><?php }
     if($arr['tecnica']){?><span class="key">Tecnica: </span><span class="value"><?php echo($arr['tecnica']); ?></span><hr><?php }
     if($arr['materiale']){?><span class="key">Materiale: </span><span class="value"><?php echo($arr['materiale']); ?></span><hr><?php }
     if($arr['stato_conservazione']){?><span class="key">Stato conservazione: </span><span class="value"><?php echo($arr['stato_conservazione']); ?></span><hr><?php }
    if(isset($_SESSION['id_user'])){
     if($arr['note']){?><span class="key">Note: </span><span class="value"><?php echo(nl2br($arr['note'])); ?></span><hr><?php }
     if($arr['funzionario']){?><span class="key">Funzionario: </span><span class="value"><?php echo($arr['funzionario']); ?></span><hr><?php }
     if($arr['link']){?><span class="key">Risorsa web: </span><span class="value"><a href="<?php echo($arr['link']); ?>" title="link esterno alla risorsa web" target='_blank'><?php echo($arr['link']); ?></a></span><hr><?php }
     if($arr['compilatore']){?><span class="key">Compilatore: </span><span class="value"><?php echo($arr['compilatore']); ?></span><hr><?php }
     if($arr['data_compilazione']){?><span class="key">Data compilazione: </span><span class="value"><?php echo($arr['data_compilazione']); ?></span><hr>
     <?php }} ?>
    </article>
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
 <footer><?php require_once("inc/footer_test.php"); ?></footer>



<div id="galleryDiv">
 <div id="galleryWrap">
  <header><i class="fa fa-times cursor"></i></header>
  <div id="fotoContent"></div>
  <span id="caption"></span>
 </div>
</div>

<div id="divFotoDialog" class="hidden">
 <form action="inc/upload_file.php" method="post" enctype="multipart/form-data" id="formFoto">
  <input type="hidden" name="poiFoto" value="<?php echo($id);?>" />
  <input type="file" name="file" id="file">
  <textarea name="descrFoto" placeholder="Inserisci una breve descrizione dell'immagine"></textarea>
  <input type="submit" name="submit" value="Carica foto">
  <input type="button" name="chiudiFoto" value="chiudi finestra" />
 </form>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 $("a.button").click(function(e){e.preventDefault();})
 var ratio = 0;
 var widthDef;
 var heightDef;
 $('.thumb')
  .css({width:'100%'})
  .click(function(){
    noScroll();
    var top = $(document).scrollTop();console.log(top);
    var src = $(this).attr('src');
    var cap = $(this).attr('title');
    var preImg = new Image();
    preImg.src = src;
    var img = $("<img />");
    img.attr("src",src);
    var w = preImg.width;
    var h = preImg.height;
    console.log("maxW "+w+' maxH '+h);
    var maxW = windowX -(windowX * 0.15);
    var maxH = windowY -(windowY * 0.15);
    
    if(w >= h){
     ratio = maxW / w;
     heightDef = h * ratio;
     if (heightDef > maxH) {
      ratio = maxH / heightDef;
      widthDef = maxW * ratio;
      $("#galleryWrap").css({"width":widthDef/*, "height":maxH*/});
      img.css({"width":widthDef, "height":maxH});
     }else {
      $("#galleryWrap").css({"width":maxW/*, "height":heightDef*/});
      img.css({"width":maxW, "height":heightDef});
     }
    }else{
     ratio = maxH / h; 
     widthDef = w * ratio;
     $("#galleryWrap").css({"width":widthDef/*,"height":maxH*/});
     img.css({"width":widthDef, "height":maxH});
    } 
    $("#fotoContent").html(img);
    $("#caption").text(cap);
    $("#galleryDiv").css({"top":top+"px"}).fadeIn('fast');
    $("#galleryWrap header i").click(function(){
     $("#galleryDiv").fadeOut('fast');
     $("#fotoContent").html('');
     scroll();
    });
    console.log(windowX+' x '+windowY);
 });
 
 $('#modFoto').click(function(){
  var modFotoPos = $('#modFoto').position();
  $('#divFotoDialog').css({'top':modFotoPos.top+125+'px'}).fadeIn('fast');
  $('input[name=chiudiFoto]').click(function(){$("#divFotoDialog").fadeOut('fast');})
 });
});

/*************************************/
/************ openlayers *************/
var map,map2, extent, osm, arrayOSM, arrayAerial, baseOSM, baseAerial, poi; //layer
var info, geolocate, filter, layers;//controlli
var lon = '<?php echo($lon); ?>';
var lat = '<?php echo($lat); ?>';
function init() {
 OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
 format = 'image/png';
 map = new OpenLayers.Map ("map", {
   controls:[
    new OpenLayers.Control.Navigation(),
    //new OpenLayers.Control.LayerSwitcher(),
    new OpenLayers.Control.MousePosition(),
    new OpenLayers.Control.Zoom(),
    new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}})
   ],
   resolutions: [156543.03390625, 78271.516953125, 39135.7584765625, 19567.87923828125, 9783.939619140625, 4891.9698095703125, 2445.9849047851562, 1222.9924523925781, 611.4962261962891, 305.74811309814453, 152.87405654907226, 76.43702827453613, 38.218514137268066, 19.109257068634033, 9.554628534317017, 4.777314267158508, 2.388657133579254, 1.194328566789627, 0.5971642833948135, 0.29858214169740677, 0.14929107084870338, 0.07464553542435169, 0.037322767712175846, 0.018661383856087923, 0.009330691928043961, 0.004665345964021981, 0.0023326729820109904, 0.0011663364910054952, 5.831682455027476E-4, 2.915841227513738E-4, 1.457920613756869E-4],
  maxExtent:new OpenLayers.Bounds (-20037508.34,-20037508.34,20037508.34,20037508.34),
   units: 'm',
   projection: new OpenLayers.Projection("EPSG:3857"),
   displayProjection: new OpenLayers.Projection("EPSG:4326")
 });

 
arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
            
baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM, {transitionEffect: "resize"});
map.addLayer(baseOSM);

var params = {srs: 'EPSG:3857',layers: 'rete:poi_rete',format: 'image/png',tiled: 'true',transparent: true , cql_filter:'id_poi=<?php echo($id); ?>'}
poi = new OpenLayers.Layer.WMS("POI", "http://37.187.200.160:8080/geoserver/rete/wms",params,{isBaseLayer: false, visibility: true});
map.addLayer(poi);

var wgs = new OpenLayers.Projection("EPSG:4326");
var utm = new OpenLayers.Projection("EPSG:3857");

var ll= new OpenLayers.LonLat(lon,lat);
var newll =  ll.transform(wgs, map.getProjectionObject());
map.setCenter(newll,15);

var ext = map.getExtent();
extent = new OpenLayers.Bounds(ext.left,ext.bottom, ext.right,ext.top);
$('.olControlZoom').append('<a href="#" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>'); 
$('.olControlZoomIn').attr("title","Ingrandisci la mappa");
$('.olControlZoomOut').attr("title","Diminuisci la mappa");
$("#max").click(function(){map.zoomToExtent(extent);});


map2 = new OpenLayers.Map ("mapPrint", {
   maxResolution: 'auto',
   maxExtent: new OpenLayers.Bounds (1160225,5397418, 1244871,5441706),
   units: 'm',
   projection: new OpenLayers.Projection("EPSG:3857")
 });

 
var arrayOSM2 = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
            
var baseOSM2 = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM2, {transitionEffect: "resize"});
map2.addLayer(baseOSM2);

var poi2 = new OpenLayers.Layer.WMS("POI", "http://37.187.200.160:8080/geoserver/rete/wms",params,{isBaseLayer: false, visibility: true});
map2.addLayer(poi2);

var wgs2 = new OpenLayers.Projection("EPSG:4326");
var utm2 = new OpenLayers.Projection("EPSG:3857");

var ll2= new OpenLayers.LonLat(lon,lat);
var newll2 =  ll2.transform(wgs2, map2.getProjectionObject());
map2.setCenter(newll2,15);

$("#print").click(function(){
 print();
 map.updateSize();
});

} //end init mappa
</script>
</body>
</html>
