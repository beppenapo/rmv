<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
   <meta name="generator" content="Bluefish 2.2.5" >
   <meta name="author" content="Giuseppe Naponiello" >
   <meta name="robots" content="INDEX,FOLLOW" />
   <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
   <link href="css/reset.css" rel="stylesheet" media="screen" />
   <link href="css/mappa.css" rel="stylesheet" media="screen" />
   <link href="css/tooltip.css" rel="stylesheet" media="screen" />
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

   <script type="text/javascript" src="js/jquery.js"></script>
   <script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
   <script src="http://maps.google.com/maps/api/js?v=3.2&amp;sensor=false"></script>
   <script type="text/javascript">
     $(document).ready(function() {
      $("#max").click(function(){map.zoomToExtent(extent);});
      $("#headerImg").click(function () {$("#result").stop().animate({left:"-31%"});});
      $("#geoloc").click(function(){geolocate.deactivate();geolocate.activate();});

      //$('#baseList li').click(function(){$(this).addClass('active').siblings().removeClass('active');});
      $(".baselayers").on('change', function(){$(this).closest('li').addClass('active').siblings('li').removeClass('active');});

     });
   </script>
 <script type="text/javascript" >
   var map, extent, gsat, osm, arrayOSM, arrayAerial, baseOSM, baseAerial, poi, highlightLayer; //layer
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
poi = new OpenLayers.Layer.WMS(
  "POI", "http://37.187.200.160:8080/geoserver/rete/wms"
  ,params
  ,{isBaseLayer: false, visibility: true});
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
       var content ="<h2> Ti trovi qui! </h2> <b>Longitudine:</b> " + lonlat.lon + "<br> <b>Latitudine:</b> " + lonlat.lat;
       geoPopup = new OpenLayers.Popup.FramedCloud("popup", lonlat, new OpenLayers.Size(250, 200), content, null, true, null);
       map.addPopup(geoPopup);
     }
  });
  markers.addMarker(marker);
} 
 
} //end init mappa
</script>
 </body>
</html>
