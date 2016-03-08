<?php
session_start();
require_once("inc/db.php");
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
<link href="css/newpoi_test.css" rel="stylesheet" media="screen" />

<title>Rete Museale del Valdarno di sotto</title>

<style>

</style>

</head>
<body onload="init()">
<header id="head"><?php require_once('inc/head.php'); ?></header>
<div id="map">
 <div id="wrapPanel">
  <div id="panelContent" class="htoolbar">
   <div id="panel" class="customEditingToolbar"></div>
   <span id="msg">Muovi il mouse all'interno della mappa tenendo premuto il tasto sinistro</span>
  </div>
  <div id="cercapoi" class="htoolbar">
   <input class="autocompletamento" id="cercapoilist" placeholder="cerca un punto di interesse" type="search"/>
  </div>
  <div id="nominatim" class="htoolbar">
   <input id="term" placeholder="cerca indirizzo" type="search"/>
   <i class="fa fa-search" id="search"></i>
   <div id="resultSearch"><ul id="resultSearchList"></ul><span id='hideSearch'>nascondi lista</span></div>
  </div>
 </div>

 <div id="menuMap">
  <h1 id="menuMapIco" class="cursor"><i class="fa fa-bars"></i></h1>
  <div id="switcher">
   <div id="switcherWrap">
    <h2 id="baseHeader">Sfondi</h2>
    <ul>
     <li><label class="layerActive"><input type="radio" class="baselayers" name="layer" id="mapquest" value="baseOSM" onclick="map.setBaseLayer(baseOSM)" checked />OpenStreetMap</label></li>
     <li><label><input type="radio" class="baselayers" name="layer" id="gsat" value="gsat" onclick="map.setBaseLayer(gsat)" />Satellite</label></li>
    </ul>
   </div>
  </div>
 </div>
</div> <!-- map -->

<div id="newPoi">
  <h1>Inserisci i dati principali per il nuovo punto di interesse</h1>
  <div id="wrapForm">
  <form>
   <input type='hidden' name='fid' id='fid' value='' />
   <input type='hidden' name='data_compilazione' id='data_compilazione' value='<?php echo(date("Y-m-d")); ?>' />
   <input type='hidden' name='id_compilatore' id='id_compilatore' value='<?php echo($_SESSION["id_user"]); ?>' />
   <table>
    <tr>
     <td><label>inv/nctn</label></td>
     <td><textarea name="inv" id="inv"></textarea></td>
    </tr>
    <tr>
     <td><label>nome sito</label></td>
     <td><textarea name="nome" id="nome" class="obbligatorio" placeholder="campo obbligatorio"></textarea></td>
    </tr>
    <tr>
     <td><label>comune</label></td>
     <td>
       <input class="autocompletamento obbligatorio" id="comuneList" placeholder="campo obbligatorio - Inizia a digitare il nome di un Comune" data-campo="comune" type="text"/>
       <input type="hidden" value="1" id="comune" />
     </td>
    </tr>
    <tr>
     <td><label>località</label></td>
     <td><select name="localita" id="localita" disabled></select></td>
    </tr>
    <tr>
     <td><label>toponimo</label></td>
     <td><select name="toponimo" id="toponimo" disabled><option value="12"></option></select></td>
    </tr>
    <tr>
     <td><label>microtoponimo</label></td>
     <td><select name="microtoponimo" id="microtoponimo" disabled><option value="1"></option></select></td>
    </tr>
    <tr>
     <td><label>posizione</label></td>
     <td><textarea name="posizione" id="posizione" class="mediumDesc"></textarea></td>
    </tr>
    <tr>
     <td><label>tipologia sito</label></td>
     <td>
      <select name="tipoSito" id="tipoSito">
       <option value=""></option>
       <?php
        $q1=("SELECT * FROM liste.sito_tipo;");
        $ex1 = pg_query($connection, $q1);
        $row1 = pg_num_rows($ex1);
        for ($x = 0; $x < $row1; $x++){
           $id_tipo = pg_result($ex1, $x,"id_sito_tipo");
           $tipo = pg_result($ex1, $x,"tipo");
           echo "<option value='$id_tipo'>$tipo</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>descrizione</label></td>
     <td><textarea name="descrizione" id="descrizione" class="longDesc obbligatorio"></textarea></td>
    </tr>
    <tr>
     <td><label>periodo</label></td>
     <td>
      <select name="periodo" id="periodo" class="obbligatorio">
       <option value="17"></option>
       <?php
        $q2=("SELECT * FROM liste.periodo_cultura where id_periodo_cultura != 17;");
        $ex2 = pg_query($connection, $q2);
        $row2 = pg_num_rows($ex2);
        for ($x = 0; $x < $row2; $x++){
           $id_periodo = pg_result($ex2, $x,"id_periodo_cultura");
           $periodo = pg_result($ex2, $x,"periodo_cultura");
           echo "<option value='$id_periodo'>$periodo</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>cronologia iniziale</label></td>
     <td><textarea name="cronoi" id="cronoi"></textarea></td>
    </tr>
    <tr>
     <td><label>cronologia finale</label></td>
     <td><textarea name="cronof" id="cronof"></textarea></td>
    </tr>
    <tr>
     <td><label>funzionario</label></td>
     <td><textarea name="funzionario" id="funzionario"></textarea></td>
    </tr>
    <tr>
     <td><label>accessibilità</label></td>
     <td>
      <select name="accessibilita" id="accessibilita">
       <option value=""></option>
       <?php
        $q3=("SELECT * FROM liste.accessibilita where id_accessibilita != 4;");
        $ex3 = pg_query($connection, $q3);
        $row3 = pg_num_rows($ex3);
        for ($x = 0; $x < $row3; $x++){
           $id_accessibilita = pg_result($ex3, $x,"id_accessibilita");
           $accessibilita = pg_result($ex3, $x,"accessibilita");
           echo "<option value='$id_accessibilita'>$accessibilita</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>definizione generale</label></td>
     <td>
      <select name="def_gen" id="def_gen" class="obbligatorio">
       <option value=""></option>
       <?php
        $q4=("SELECT * FROM liste.definizione_generale order by id_def_generale;");
        $ex4 = pg_query($connection, $q4);
        $row4 = pg_num_rows($ex4);
        for ($x = 0; $x < $row4; $x++){
           $id_def_generale = pg_result($ex4, $x,"id_def_generale");
           $def_gen = pg_result($ex4, $x,"def_generale");
           $ico = pg_result($ex4, $x,"ico");
           echo "<option value='$id_def_generale' data-ico='$ico'>$def_gen</option>";
         }
       ?>
      </select>
      <input type="hidden" name="ico" id="ico" value='' />
     </td>
    </tr>
    <tr>
     <td><label>definizione specifica</label></td>
     <td><select name="def_spec" id="def_spec" disabled></select></td>
    </tr>
    <tr>
     <td><label>stato di conservazione</label></td>
     <td>
      <select name="conservazione" id="conservazione">
       <option value=""></option>
       <?php
        $q5=("SELECT * FROM liste.stato_conservazione order by id_stato_conservazione asc;");
        $ex5 = pg_query($connection, $q5);
        $row5 = pg_num_rows($ex5);
        for ($x = 0; $x < $row5; $x++){
           $id_conservazione = pg_result($ex5, $x,"id_stato_conservazione");
           $conservazione = pg_result($ex5, $x,"stato_conservazione");
           echo "<option value='$id_conservazione'>$conservazione</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>materiale</label></td>
     <td>
      <select name="materiale" id="materiale">
       <option value="11"></option>
       <?php
        $q6=("SELECT * FROM liste.materiale order by materiale asc;");
        $ex6 = pg_query($connection, $q6);
        $row6 = pg_num_rows($ex6);
        for ($x = 0; $x < $row6; $x++){
           $id_materiale = pg_result($ex6, $x,"id_materiale");
           $materiale = pg_result($ex6, $x,"materiale");
           echo "<option value='$id_materiale'>$materiale</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>tecnica</label></td>
     <td><select name="tecnica" id="tecnica" disabled><option value="27"></option></select></td>
    </tr>
    <tr>
     <td><label>contatti</label></td>
     <td><textarea name="contatti" id="contatti" class="longDesc"></textarea></td>
    </tr>
    <tr>
     <td><label>risorsa web</label></td>
     <td><textarea name="link" id="link"></textarea></td>
    </tr>
    <tr>
     <td><label>note</label></td>
     <td><textarea name="note" id="note" class="longDesc"></textarea></td>
    </tr>
   </table>
  </form>
  <div class="error"></div>
  <div style="position:relative; width:80%; margin:10px auto;">
   <button class="submitButton" id="salvaDati">Salva dati</button>
   <button class="submitButton" id="annullaInserimento">Annulla inserimento</button>
  </div>
 </div>
 </div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jq-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/openlayers/lib/OpenLayers.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyAjIFKh5283gkT3TEdbrjxzm1-sFQppG1Y" type="text/javascript"></script>
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript" src="js/dinSelect.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 $("#newPoi,#resultSearch").hide();
 $('#annullaInserimento').click(function(){location.reload();});

 $('#salvaDati').click(onTriggerInsertar);

 $("#search").click(function(){
  var q = $("#term").val();
  geocode(q);
 });
});



function geocode(q) {
  $.getJSON('http://nominatim.openstreetmap.org/search?format=json&q=' + q, function(data) {
   var items = [];
   $.each(data, function(key, val) {
    items.push("<li data-extent='"+val.boundingbox+"' data-lat='"+val.lat +"' data-lon='"+ val.lon +"'>"+ val.display_name + " ("+val.type+")</li>");
   });
   $("#resultSearchList").html(items);
   $("#resultSearch").fadeIn('fast');
   $("#hideSearch").click(function(){
    $("#resultSearch").fadeOut('fast');
    $("#resultSearchList").html('');
   });

   $("#resultSearchList > li").click(function(){
    //$("#resultSearch").fadeOut('fast');
    var newExt = $(this).data('extent');
    newExt = newExt.split(',');
    //new OpenLayers.Bounds (1160225,5397418, 1244871,5441706)
    //40.4810788,40.6520756,14.9060393,15.0985536
    var b = new OpenLayers.Bounds(newExt[2], newExt[0], newExt[3], newExt[1]);
    var p3857 = new OpenLayers.Projection("EPSG:3857");
    var p4326 = new OpenLayers.Projection("EPSG:4326");
    b.transform(p4326, p3857);
    map.zoomToExtent(b);
    //console.log(b);
   });
  });
}


//variabili jQuery
var switcherWidth, resultWidth;
//variabili OL
var map, extent, gsat, osm, arrayOSM, arrayAerial, baseOSM, baseAerial, poi, highlightLayer,featHiLite; //layer
var info, geolocate, filter, layers,selectFeatureControl, stile;//controlli

$(document).ready(function() {

 if(windowX < 481){ //smartphone
  switcherWidth = "150px";
  resultWidth = "99%";
  $(".switcherIco").remove();
 }
 else if(windowX < 1024){ //tablet
  switcherWidth = "180px";
  resultWidth = "250px";
 }
 else { //pc
  switcherWidth = "200px";
  resultWidth = "350px";
 }

 $("#result").css("width", resultWidth);

 $("#mappaLink").addClass('active').click(function(e){e.preventDefault();});

 $("#map").height(windowY-headH-5);
 $("#menuMap").css("width", "22px");

 $("#switcher, #result").hide();
 $("#menuMapIco").clickToggle(
  function(){
   $("#menuMap").css("width", switcherWidth);
   $("#switcher").show();
  },
  function(){
   $("#menuMap").css("width", "22px");
   $("#switcher").hide();
  }
 );

 $(".baselayers").on('change', function(){
  $(".baselayers").closest('label').removeClass('layerActive');
  $(this).closest('label').addClass('layerActive');
 });
});
function init() {
 OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
 format = 'image/png';
 var msgDel = "Geometria eliminata!\n Per rendere effettiva l'eliminazione\n utilizza il tasto 'Salva' a destra nel menù delle funzioni";
 var msgIns = "Ok! Salvataggio avvenuto correttamente";
 var msgUpdate = "Ok! La geometria è stata modificata";

 var DeleteFeature = OpenLayers.Class(OpenLayers.Control, {
   initialize: function(layer, options) {
     OpenLayers.Control.prototype.initialize.apply(this, [options]);
     this.layer = layer;
     this.handler = new OpenLayers.Handler.Feature(
       this, layer, {click: this.clickFeature}
     );
   },
   clickFeature: function(feature) {
      // if feature doesn't have a fid, destroy it
      if(feature.fid == undefined) {
        this.layer.destroyFeatures([feature]);
      } else {
        feature.state = OpenLayers.State.DELETE;
        this.layer.events.triggerEvent("afterfeaturemodified",{feature: feature});
        feature.renderIntent = "select";
        this.layer.drawFeature(feature);
        $('#msg').text(msgDel);
      }
    },
    setMap: function(map) {
        this.handler.setMap(map);
        OpenLayers.Control.prototype.setMap.apply(this, arguments);
    },
    CLASS_NAME: "OpenLayers.Control.DeleteFeature"
});

 map = new OpenLayers.Map ("map", {
   controls:[
    new OpenLayers.Control.Navigation(),
    new OpenLayers.Control.MousePosition(),
    new OpenLayers.Control.Zoom(),
    new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}})
   ],
   maxResolution: [156543.03390625, 78271.516953125, 39135.7584765625, 19567.87923828125, 9783.939619140625, 4891.9698095703125, 2445.9849047851562, 1222.9924523925781, 611.4962261962891, 305.74811309814453, 152.87405654907226, 76.43702827453613, 38.218514137268066, 19.109257068634033, 9.554628534317017, 4.777314267158508, 2.388657133579254, 1.194328566789627, 0.5971642833948135, 0.29858214169740677, 0.14929107084870338, 0.07464553542435169, 0.037322767712175846, 0.018661383856087923, 0.009330691928043961, 0.004665345964021981, 0.0023326729820109904, 0.0011663364910054952, 5.831682455027476E-4, 2.915841227513738E-4, 1.457920613756869E-4],
   maxExtent: new OpenLayers.Bounds (-20037508.34,-20037508.34,20037508.34,20037508.34),
   units: 'm',
   projection: new OpenLayers.Projection("EPSG:3857"),
   displayProjection: new OpenLayers.Projection("EPSG:4326")
 });


arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];

baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM, {
                transitionEffect: "resize"
            });
map.addLayer(baseOSM);

gsat = new OpenLayers.Layer.Google("Hybrid", {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22});
//gsat = new OpenLayers.Layer.WMS("real", "http://213.215.135.196/reflector/open/service?", {layers: 'rv1', format: 'image/jpeg', attribution: "RealVista1.0 WMS OPEN di e-GEOS SpA - CC BY SA"});
map.addLayer(gsat);


stile = new OpenLayers.StyleMap({
   "select": new OpenLayers.Style({graphicName: "circle",pointRadius: 15,strokeColor: "#1D22CF",fillColor: "#1D22CF", fillOpacity:0, graphicZIndex: 2}),
   "default": new OpenLayers.Style({fillOpacity:0,strokeOpacity:0}),
   "active": new OpenLayers.Style({fillColor: "#7578F5", fillOpacity:0.6, graphicZIndex: 2})
});

extent = new OpenLayers.Bounds(1160225,5397418, 1244871,5441706);
layers=[poi];
console.log('cql_filter: '+filter);

var styleSito = new OpenLayers.StyleMap({
 "default": new OpenLayers.Style(null, {
  rules: [
   new OpenLayers.Rule({
    symbolizer: {
     pointRadius: 8,
     fillColor: "#427109",
     fillOpacity: 1,
     strokeWidth: 2,
     strokeColor: "#72B51E"
    }
   })
  ]
 }),
 "select": new OpenLayers.Style({
  fillColor: "#0C06AF",
  strokeColor: "#00ccff",
  strokeWidth: 1
 }),
 "temporary": new OpenLayers.Style(null, {
  rules: [
   new OpenLayers.Rule({
    symbolizer: {
     pointRadius: 8,
     fillColor: "#0C06AF",
     fillOpacity: 1,
     strokeWidth: 1,
     strokeColor: "#333333"
    }
   })
  ]
 })
});

var saveStrategy = new OpenLayers.Strategy.Save();
punti = new OpenLayers.Layer.Vector("wfs", {
 styleMap: styleSito,
 strategies: [new OpenLayers.Strategy.BBOX(), saveStrategy],
 protocol: new OpenLayers.Protocol.WFS({
  version:       "1.0.0",
  url:           "http://37.187.200.160:8080/geoserver/rete/wfs",
  featureType:   "sito",
  srsName:       "EPSG:3857",
  featureNS:     "http://37.187.200.160",
  geometryName:  "the_geom",
  schema:        "http://37.187.200.160?service=WFS&version=1.0.0&request=DescribeFeatureType&TypeName=rete:sito"
 })
 /*,filter: new OpenLayers.Filter.Comparison({
  type: OpenLayers.Filter.Comparison.EQUAL_TO,
  property: "id_sito",
  value: "<?php echo($idSito); ?>"
 })*/
});
map.addLayer(punti);
//punti.setVisibility(false);

// add the custom editing toolbar
 navigate = new OpenLayers.Control.DragPan({
   isDefault: true,
   title: "Muovi il mouse all'interno della mappa tenendo premuto il tasto sinistro",
   displayClass: "olControlNavigation"
 });
 save = new OpenLayers.Control.Button({
         title: "Salva le modifiche effettuate e chiudi la sessione di lavoro",
         trigger: function() {
           if(edit.feature) {$('#msg').text(msgUpdate);}else{$('#msg').text(msgIns);}
           saveStrategy.save();
         },
         displayClass: "olControlSaveFeatures"
 });
 del = new DeleteFeature(punti, {title: "Elimina geometria"});
 draw = new OpenLayers.Control.DrawFeature(punti, OpenLayers.Handler.Point,{
    title: "Disegna punto",
    displayClass:"olControlDrawFeaturePoint",
    //handlerOptions: {multi: true},
    featureAdded: onFeatureInsert
 });
 edit = new OpenLayers.Control.ModifyFeature(punti, {
        title: "Modifica vertici geometria",
        displayClass: "olControlModifyFeature"
        //,vertexRenderIntent: "vertex"
 });

var divPannello = document.getElementById("panel");
 panel = new OpenLayers.Control.Panel({
       defaultControl: navigate,
       displayClass: 'olControlPanel',
       div: divPannello
 });
 panel.addControls([navigate,draw,edit,del,save]);

 map.addControl(panel);


  $('#panel div').each(function(){
   $(this).click(function(){
    var text = $(this).attr('title');
    console.log("t:"+text);
    $('#msg').text(text);
   });
  });



if (!map.getCenter()) {map.zoomToExtent(extent);}

$('.olControlZoom').append('<a href="#" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>');
$('.olControlZoomIn').attr("title","Ingrandisci la mappa");
$('.olControlZoomOut').attr("title","Diminuisci la mappa");
$("#max").click(function(){map.zoomToExtent(extent);});
} //end init mappa

// PopUp insert/update
function onFeatureInsert(feature){
   selectedFeature = feature;
   var fid = selectedFeature.id;
   var gid = selectedFeature.attributes['gid'];
   $('#fid').val(fid);
   $('#newPoi').show();
}

  // Passa attributi al form
 var btnInsert = new OpenLayers.Control.Button({trigger: onTriggerInsertar});

function onTriggerInsertar(fid){
 var errori = "Prima di proseguire correggi i seguenti errori:<br/>";
 var sito = $('#nome').val();
 if(!sito){errori += 'Inserisci un nome per il sito<br/>'; $('#nome').addClass('errorClass');}
 else{$('#nome').removeClass('errorClass');};

 var comune = $('#comuneList').val();
 if(!comune){errori += 'Seleziona un Comune dalla lista<br/>'; $('#comuneList').addClass('errorClass');}
 else{$('#comuneList').removeClass('errorClass');};

 var tipoSito= $('#tipoSito').val();
 if(!tipoSito){errori += 'Seleziona una tipologia per il sito<br/>'; $('#tipoSito').addClass('errorClass');}
 else{$('#tipoSito').removeClass('errorClass');};

 var descrizione = $('#descrizione').val();
 if(!descrizione){errori += 'Inserisci una descrizione anche breve<br/>'; $('#descrizione').addClass('errorClass');}
 else{$('#descrizione').removeClass('errorClass');};

 var periodo = $('#periodo').val();
 if(!periodo){errori += 'Seleziona un periodo<br/>'; $('#periodo').addClass('errorClass');}
 else{$('#periodo').removeClass('errorClass');};

 var accessibilita = $('#accessibilita').val();
 if(!accessibilita){errori += 'Seleziona il tipo di accesso al sito<br/>'; $('#accessibilita').addClass('errorClass');}
 else{$('#accessibilita').removeClass('errorClass');};

 var defGen = $('#def_gen').val();
 if(!defGen){errori += 'Seleziona una definizione generale che identifichi il sito<br/>'; $('#def_gen').addClass('errorClass');}
 else{$('#def_gen').removeClass('errorClass');};

 var statoCons = $('#conservazione').val(); if(!statoCons){errori += 'Definisci lo stato di conservazione<br/>'};

 var link = $("#link").val();
 if(link){
    var urlregex = new RegExp("^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
    if (urlregex.test(link)) {
        $('#link').removeClass('errorClass');
    }else{
        errori += 'Inserisci un link valido<br/>'; $('#link').addClass('errorClass');
    }
 }

  if(!sito || !comune || !tipoSito || !descrizione || !periodo || !accessibilita || !defGen || !statoCons){
    $('.error').html(errori); return false;
  }else{
   $('.error').html('');
   var fid =  $('#fid').val();
   var miFeature = punti.getFeatureById(fid);
   miFeature.attributes.inv = $('#inv').val();
   miFeature.attributes.sito_nome = $('#nome').val();
   miFeature.attributes.id_comune = $('#comune').val();
   miFeature.attributes.id_localita = $('#localita').val();
   miFeature.attributes.id_toponimo = $('#toponimo').val();
   miFeature.attributes.id_microtoponimo = $('#microtoponimo').val();
   miFeature.attributes.posizione = $('#posizione').val();
   miFeature.attributes.descrizione = $('#descrizione').val();
   miFeature.attributes.id_periodo = $('#periodo').val();
   miFeature.attributes.crono_iniziale = $('#cronoi').val();
   miFeature.attributes.crono_finale = $('#cronof').val();
   miFeature.attributes.funzionario = $('#funzionario').val();
   miFeature.attributes.id_accessibilita = $('#accessibilita').val();
   miFeature.attributes.id_def_generale = $('#def_gen').val();
   miFeature.attributes.id_def_specifica = $('#def_spec').val();
   miFeature.attributes.id_stato_conservazione = $('#conservazione').val();
   miFeature.attributes.id_materiale = $('#materiale').val();
   miFeature.attributes.id_tecnica = $('#tecnica').val();
   miFeature.attributes.id_icone = $('input[name=ico]').val();
   miFeature.attributes.data_compilazione = $('#data_compilazione').val();
   miFeature.attributes.id_compilatore = $('#id_compilatore').val();
   miFeature.attributes.note = $('#note').val();
   miFeature.attributes.contatti = $('#contatti').val();
   miFeature.attributes.id_sito_tipo = $('#tipoSito').val();
   miFeature.attributes.link = $('#link').val();
   $('#newPoi').hide();
   $('#msg').text('<- Clicca il tasto per salvare i dati e chiudere la sessione di editing.')
  }
 }
var btnUpdate = new OpenLayers.Control.Button({trigger: onTriggerUpdate});
function onTriggerUpdate(){
   miFeature = [selectedFeature];
   var fid =  OpenLayers.Util.getElement('fid').value;
   miFeature[0].id = fid;
   miFeature[0].attributes.inv = $('#inv').val();
   miFeature[0].attributes.sito_nome = $('#nome').val();
   miFeature[0].attributes.id_comune = $('#comune').val();
   miFeature[0].attributes.id_localita = $('#localita').val();
   miFeature[0].attributes.id_toponimo = $('#toponimo').val();
   miFeature[0].attributes.id_microtoponimo = $('#microtoponimo').val();
   miFeature[0].attributes.posizione = $('#posizione').val();
   miFeature[0].attributes.descrizione = $('#descrizione').val();
   miFeature[0].attributes.id_periodo = $('#periodo').val();
   miFeature[0].attributes.crono_iniziale = $('#cronoi').val();
   miFeature[0].attributes.crono_finale = $('#cronof').val();
   miFeature[0].attributes.funzionario = $('#funzionario').val();
   miFeature[0].attributes.id_accessibilita = $('#accessibilita').val();
   miFeature[0].attributes.id_def_generale = $('#def_gen').val();
   miFeature[0].attributes.id_def_specifica = $('#def_spec').val();
   miFeature[0].attributes.id_stato_conservazione = $('#conservazione').val();
   miFeature[0].attributes.id_materiale = $('#materiale').val();
   miFeature[0].attributes.id_tecnica = $('#tecnica').val();
   miFeature[0].attributes.id_icone = $('input[name=ico]').val();
   miFeature[0].attributes.data_compilazione = $('#data_compilazione').val();
   miFeature[0].attributes.id_compilatore = $('#id_compilatore').val();
   miFeature[0].attributes.note = $('#note').val();
   miFeature[0].attributes.contatti = $('#contatti').val();
   miFeature[0].attributes.id_sito_tipo = $('#tipoSito').val();
   miFeature[0].attributes.link = $('#link').val();
   miFeature[0].state = OpenLayers.State.UPDATE;
 }
</script>
</body>
</html>
