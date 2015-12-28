<?php 
session_start();
require_once("inc/db.php");
$lastPoiQuery=("select max(gid) as last from poi;");
$exec = pg_query($connection, $lastPoiQuery);
$arr = pg_fetch_array($exec, 0, PGSQL_ASSOC);
$lastPoi = $arr['last'];
$data = date("Y-m-d");
$compilatore = $_SESSION['id_user'];
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
 <link href="css/newpoi.css" rel="stylesheet" media="screen" />
 <link href="css/tooltip.css" rel="stylesheet" media="screen" />
 <link href="js/jq-ui/css/smoothness/jquery-ui-1.10.4.custom.min.css" type="text/css" rel="stylesheet" media="screen" />
 <link href="js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
 <title>Rete Museale del Valdarno di sotto</title>
</head>
<body onload="init()">
 <div id="head">
  <div id="headLeft">
   <img src="img/logoBanner.png" alt="logo rete museale Valdarno" title="Logo della rete museale del Valdarno" />
   <h1><span class="blu">V</span>aldarno <span class="red">M</span>usei</h1>
  </div>
  <div id="nav">
   <ul>
    <li><a href="index.php" class="mainLink tooltip">home<span><b></b>torna alla pagina iniziale del sito</span></a></li>
    <li><a href="mappa.php" class="mainLink tooltip">mappa<span><b></b>accedi alla mappa</span></a></li>
    <li><a href="poiList.php" class="mainLink tooltip">poi<span><b></b>visualizza la lista completa dei punti di interesse</span></a></li>
    <li><a href="" class="mainLink tooltip">info<span><b></b>informazioni sul sito</span></a></li>
   </ul>
  </div>
 </div>

 <div id="map"></div>
 <div id="wrapPanel">
  <div id="panelContent">
   <div id="panel" class="customEditingToolbar"></div>
   <span id="msg">Muovi il mouse all'interno della mappa tenendo premuto il tasto sinistro</span>
  </div>
 </div>
 <div id="controlPanelMio">
  <a href="#zoomIn" id="customZoomIn"><img src="img/ico/plus.png" /></a>
  <a href="#zoomOut" id="customZoomOut"><img src="img/ico/menus.png" /></a>
  <a href="#" id="max"><img src="img/ico/zoom_mondo.png" /></a>
  <a href="#" id="geoloc"><img src="img/ico/geoloc.png" /></a>
 </div>

 <div id="switcherMio">
  <h1 class="titoloSezione">Cartografia di base</h1>
  <div class="layers" id="base">
   <ul id="baseList">
    <li class="layerList active"><label><input type="radio" class="baselayers" name="layer" id="mapquest" value="baseOSM" onclick="map.setBaseLayer(baseOSM)" checked /> MapQuest</label></li>	 
    <li class="layerList"><label><input type="radio" class="baselayers" name="layer" id="gsat" value="gsat" onclick="map.setBaseLayer(gsat)" /> Google Hybrid</label></li>	
   </ul>
  </div>

  <h1 class="titoloSezione">Tipologia sito</h1>	
  <div class="layers" id="tipologia">		
   <ul id="overlayList" class="layerList">
    <li id="icoarearch" class="active"><label class="ol"><img src="img/ico/area_arch.png" />Aree archeologiche<input type="checkbox" id="areearch" class="overlayers" name="tipologia" value="1" checked/></label></li>		 
    <li id="icomusei" class="active"><label class="ol"><img src="img/ico/musei.png" />Musei e raccolte<input type="checkbox" id="musei" class="overlayers" name="tipologia" value="2" checked/></label></li>
    <li id="icopalazzo" class="active"><label class="ol"><img src="img/ico/palazzo.png" />Edifici civili<input type="checkbox" id="edcivili" class="overlayers" name="tipologia" value="3" checked/></label></li>
    <li id="icoattpro" class="active"><label class="ol"><img src="img/ico/att_produttiva.png" />Attività produttive<input type="checkbox" id="attprod" class="overlayers" name="tipologia" value="4" checked/></label></li>
    <li id="icostrdif" class="active"><label class="ol"><img src="img/ico/strutt_dif.png" />Strutture difensive<input type="checkbox" id="struttdif" class="overlayers" name="tipologia" value="5" checked/></label></li>
    <li id="icochiesa" class="active"><label class="ol"><img src="img/ico/chiesa.png" />Edifici religiosi<input type="checkbox" id="edreligiosi" class="overlayers" name="tipologia" value="6" checked/></label></li>
    <li id="icocisterna" class="active"><label class="ol"><img src="img/ico/cisterna.png" />Fonti idriche<input type="checkbox" id="cisterne" class="overlayers" name="tipologia" value="7" checked/></label></li>
    <li id="icond" class="active"><label class="ol"><img src="img/ico/non_determinabile.png" />Non determinabile<input type="checkbox" id="nd" class="overlayers" name="tipologia" value="8" checked/></label></li>
   </ul>
  </div><!--tipologia-->	

  <h1 class="titoloSezione">Percorsi suggeriti</h1>	
  <div class="layers" id="tipologia"></div>
 </div>

 <div id="resultWrap"><div id="result"><div id="headerImg">&#10006;</div><div id="resultContent"></div></div></div>

 <div id="newPoi">
  <h1>Inserisci i dati principali per il nuovo punto di interesse</h1>
  <div id="wrapForm">
  <form>
   <input type='hidden' name='fid' id='fid' value='' />
   <input type='hidden' name='data_compilazione' id='data_compilazione' value='<?php echo($data); ?>' />
   <input type='hidden' name='id_compilatore' id='id_compilatore' value='<?php echo($compilatore); ?>' />
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
       <input class="autocompletamento obbligatorio" id="comuneList" placeholder="campo obbligatorio - Inizia a digitare il nome di un Comune" data-campo="comune"/>   
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
      <input type="hidden" id="ico" value='' />
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

<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script src="http://maps.google.com/maps/api/js?v=3.2&amp;sensor=false"></script>

<script type="text/javascript" src="js/dinSelect.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
  $("#newPoi").hide();
  $("#max").click(function(){map.zoomToExtent(extent);});
  $("#headerImg").click(function () {$("#result").stop().animate({left:"-31%"});});
  $("#geoloc").click(function(){geolocate.deactivate();geolocate.activate();});
  //$('#baseList li').click(function(){$(this).addClass('active').siblings().removeClass('active');});
  $(".baselayers").on('change', function(){$(this).closest('li').addClass('active').siblings('li').removeClass('active');});

  $('#annullaInserimento').click(function(){location.reload();});

$('#salvaDati').click(onTriggerInsertar);
 
  
});
</script>
<script type="text/javascript" >
   var map, extent, gsat, osm, arrayOSM, arrayAerial, baseOSM, baseAerial, poi, punti, highlightLayer; //layer
   var info, geolocate, filter, layers;//controlli

$(".overlayers").on('change', function(){
   $(this).closest('li').toggleClass('active');
   poi.setVisibility(false);
   filter = '';
   var length=$('input[name="tipologia"]:checked').length;
   //console.log("checked= "+length);
   if(length==0){poi.setVisibility(false);return;}
   else if(length==8){
     filter='';
     filter = 'id_icone>0';
     info.cql_filter=filter;
     poi.params.CQL_FILTER = filter; 
     poi.setVisibility(true);
     //poi.redraw();
     return;
   }
   else{ 
    $('input[name="tipologia"]:checked').each(function(){
      var param = $(this).val();
      filter += 'id_icone='+param+' OR ';
    });
    filter = filter.slice(0,-4);
    //console.log(filter);
    poi.params.CQL_FILTER = filter;  
    info.cql_filter=filter;  
   }
   poi.setVisibility(true);
   poi.redraw();
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
    //new OpenLayers.Control.LayerSwitcher(),
    new OpenLayers.Control.MousePosition(),
    new OpenLayers.Control.Zoom({
            zoomInId: "customZoomIn",
            zoomOutId: "customZoomOut"
        }),
    new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}})
   ],
   maxResolution: 'auto',
   //restrictedExtent: new OpenLayers.Bounds(1191433.64, 5407975.49, 1211503.60, 5417881.73),
   maxExtent: new OpenLayers.Bounds (1160225,5397418, 1244871,5441706),
   //allOverlays: true,
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
map.addLayer(gsat);

var params = {srs: 'EPSG:3857',layers: 'rete:poi_rete',format: 'image/png',tiled: 'true',transparent: true, cql_filter:filter}
poi = new OpenLayers.Layer.WMS("POI", "http://37.187.200.160:8080/geoserver/rete/wms",params,{isBaseLayer: false, visibility: false});
map.addLayer(poi);


var s = new OpenLayers.StyleMap({
   "default": new OpenLayers.Style({fillOpacity:0,strokeOpacity:0}),
   "select": new OpenLayers.Style({graphicName: "square",pointRadius: 10,fillColor: "#1D22CF", fillOpacity:0.8, graphicZIndex: 2})
});

highlightLayer = new OpenLayers.Layer.Vector("Highlighted", {
      strategies: [new OpenLayers.Strategy.BBOX()],
      styleMap: s,
      protocol: new OpenLayers.Protocol.WFS({
          version:     "1.0.0",
          url:         "http://37.187.200.160:8080/geoserver/rete/wfs",
          featureType: "poi_rete",
          featureNS:   "http://37.187.200.160",
          srsName:     "EPSG:3857",
          geometryName:"the_geom",
          schema:      "http://37.187.200.160:8080/geoserver/rete/wfs?service=WFS&version=1.0.0&request=DescribeFeatureType&typeName=rete%3Apoi_rete"
      })
});
map.addLayer(highlightLayer);


 //var scalebar = new OpenLayers.Control.ScaleBar({div:document.getElementById("scalebar")});
 //map.addControl(scalebar);

extent = new OpenLayers.Bounds(1160225,5397418, 1244871,5441706);
layers=[poi];
console.log('cql_filter: '+filter);




info = new OpenLayers.Control.WMSGetFeatureInfo({
    url: 'http://37.187.200.160:8080/geoserver/rete/wms', 
    title: 'Informazioni sui livelli interrogati',  
    queryVisible: true,
    //cql_filter: filter,
    layers: layers,
    infoFormat: 'application/vnd.ogc.gml',
    //vendorParams: {buffer: 10},
    eventListeners: {
        getfeatureinfo: function(event) {
            var arr = new Array();
            for (var i = 0; i < event.features.length; i++) { 
               var feature = event.features[i]; 
               var attributes = feature.attributes; 
               var id_poi = attributes.id_poi;  
               arr.push(id_poi);                      
            }
            $.ajax({
                 url: 'inc/popupPoi.php',
                 type: 'POST',
                 data: {arr:arr},
                 success: function(data){
                   $("#result").stop().animate({left:"0px"});
                   $("#resultContent").html(data);
                 }
            });//ajax*/
            //console.log(arr);
        },
        beforegetfeatureinfo: function(event){
          var lyrCQL = poi.params.CQL_FILTER;
          if (lyrCQL != null) {filter = lyrCQL;}
          info.vendorParams = { 'CQL_FILTER': filter,buffer: 10	};
        }
    }
});
map.addControl(info);
info.activate();

var styleSito = new OpenLayers.StyleMap({
 "default": new OpenLayers.Style(null, {
     rules: [
         new OpenLayers.Rule({
             symbolizer: {
                     pointRadius: 8,
                     fillColor: "#427109",
                     fillOpacity: 1,
                     strokeWidth: 2,
                     //strokeOpacity: 1,
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
                     //strokeOpacity: 1,
                     strokeColor: "#333333"
             }
         })
     ]
 })
});

var saveStrategy = new OpenLayers.Strategy.Save();
punti = new OpenLayers.Layer.Vector("wfs", {
    //scales: map.getScale (2,10),
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



// add the custom editing toolbar
 navigate = new OpenLayers.Control.DragPan({
   isDefault: true,
   title: "Muovi il mouse all'interno della mappa tenendo premuto il tasto sinistro",
   displayClass: "olControlNavigation"
 });
 save = new OpenLayers.Control.Button({
         title: "Salva le modifiche effettuate e chiudi la sessione di lavoro",
         trigger: function() {
           if(edit.feature) {edit.selectControl.unselectAll(); $('#msg').text(msgUpdate);}else{$('#msg').text(msgIns);}
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
 
 
//geolocalizzazione
geolocate = new OpenLayers.Control.Geolocate({
  eventListeners: {
      "locationupdated": locateMarker,
      "locationfailed": function() { console.log('Non è stato possibile individuare la tua posizione!'); }
  }
});
map.addControl(geolocate);
var markers = new OpenLayers.Layer.Markers("Markers");
map.addLayer(markers);
    	
function locateMarker(event) {
  // Rimuovi marker esistenti: ad ogni chiamata della funzione bisogna pulire la mappa dai precedenti risultati
  markers.clearMarkers();
        
  var size = new OpenLayers.Size(32, 37);
  var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
  var icon = new OpenLayers.Icon('./img/ico/geoloc.png', size, offset);
  icon.setOpacity(1);
        
  // recupera lonlat dalla posizione (event) dell'utente
  // NOTE: la funzione geolocate trasforma lonlat nella proiezione usata per la mappa
  var lonlat = new OpenLayers.LonLat(event.point.x, event.point.y);
   
  // Aggiungi il marker con la nuova posizione
  var geoPopup = null;
  var marker = new OpenLayers.Marker(lonlat, icon);
  var ext = new OpenLayers.Bounds(lonlat);
  map.zoomToExtent(ext);
        
  marker.events.on({
     "mouseover": function() { 
       if(geoPopup) { map.removePopup(geoPopup); }
       var content ="<h2> Ti trovi qui! </h2> <b>Longitudine:</b> " + lonlat.lon + "<br/> <b>Latitudine:</b> " + lonlat.lat;
       geoPopup = new OpenLayers.Popup.FramedCloud("popup", lonlat, new OpenLayers.Size(250, 200), content, null, true, null);
       map.addPopup(geoPopup);
     }
  });
  markers.addMarker(marker);
} 

} //end init mappa

 // PopUp para insert/update
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
   miFeature.attributes.id_icone = $('#ico').val();
   miFeature.attributes.data_compilazione = $('#data_compilazione').val();
   miFeature.attributes.id_compilatore = $('#id_compilatore').val();
   miFeature.attributes.note = $('#note').val();
   miFeature.attributes.contatti = $('#contatti').val();
   miFeature.attributes.id_sito_tipo = $('#tipoSito').val();
   //console.log("comune gid: "+miFeature.attributes.id_comune);
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
   miFeature[0].attributes.id_icone = $('#ico').val();
   miFeature[0].attributes.data_compilazione = $('#data_compilazione').val();
   miFeature[0].attributes.id_compilatore = $('#id_compilatore').val();
   miFeature[0].attributes.note = $('#note').val();
   miFeature[0].attributes.contatti = $('#contatti').val();
   miFeature[0].attributes.id_sito_tipo = $('#tipoSito').val();
   miFeature[0].state = OpenLayers.State.UPDATE;
 }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
</script>
 </body>
</html>
