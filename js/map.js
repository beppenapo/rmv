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

 $("#resultHeader").click(function(){ $("#result").fadeOut('fast');} );

 $(".baselayers").on('change', function(){
  $(".baselayers").closest('label').removeClass('layerActive');
  $(this).closest('label').addClass('layerActive');
 });

 $(".overlayers").on('change', function(){
   if($(this).is(':checked')){$(this).prev('img').fadeTo('slow', 1);}else{$(this).prev('img').fadeTo('slow', 0.3);}
   $(this).closest('label').toggleClass('layerActive');
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
});


function init() {
 OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
 format = 'image/png';
 map = new OpenLayers.Map ("map", {
   controls:[
    new OpenLayers.Control.Navigation(),
    new OpenLayers.Control.MousePosition(),
    new OpenLayers.Control.Zoom(),
    new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}})
   ],
   resolutions: [156543.03390625, 78271.516953125, 39135.7584765625, 19567.87923828125, 9783.939619140625, 4891.9698095703125, 2445.9849047851562, 1222.9924523925781, 611.4962261962891, 305.74811309814453, 152.87405654907226, 76.43702827453613, 38.218514137268066, 19.109257068634033, 9.554628534317017, 4.777314267158508, 2.388657133579254, 1.194328566789627, 0.5971642833948135, 0.29858214169740677, 0.14929107084870338, 0.07464553542435169, 0.037322767712175846, 0.018661383856087923, 0.009330691928043961, 0.004665345964021981, 0.0023326729820109904, 0.0011663364910054952, 5.831682455027476E-4, 2.915841227513738E-4, 1.457920613756869E-4],
   maxExtent: new OpenLayers.Bounds (-20037508.34,-20037508.34,20037508.34,20037508.34),
   units: 'm',
   projection: new OpenLayers.Projection("EPSG:3857"),
   displayProjection: new OpenLayers.Projection("EPSG:4326")
 });


arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];

//baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM, {transitionEffect: "resize"});
baseOSM = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
map.addLayer(baseOSM);

gsat = new OpenLayers.Layer.Google("Hybrid", {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22});
map.addLayer(gsat);

stile = new OpenLayers.StyleMap({
   "select": new OpenLayers.Style({graphicName: "circle",pointRadius: 15,strokeColor: "#1D22CF",fillColor: "#1D22CF", fillOpacity:0, graphicZIndex: 2}),
   "default": new OpenLayers.Style({fillOpacity:0,strokeOpacity:0}),
   "active": new OpenLayers.Style({fillColor: "#7578F5", fillOpacity:0.6, graphicZIndex: 2})
});

poi = new OpenLayers.Layer.WMS("POI", "http://37.187.200.160:8080/geoserver/rete/wms"
 ,{srs: 'EPSG:3857' ,layers: 'rete:poi_rete' ,format: 'image/png' ,tiled: 'true' ,transparent: true , cql_filter:filter}
 ,{isBaseLayer: false, visibility: true}
);
map.addLayer(poi);

highlightLayer = new OpenLayers.Layer.Vector("Highlighted", {
  strategies: [new OpenLayers.Strategy.BBOX()]
 ,styleMap: stile
 ,protocol: new OpenLayers.Protocol.WFS({
          version:     "1.0.0",
          url:         "http://37.187.200.160:8080/geoserver/rete/wfs",
          featureType: "poi_rete",
          featureNS:   "http://37.187.200.160",
          srsName:     "EPSG:3857",
          geometryName:"the_geom",
          //schema:      "http://37.187.200.160:8080/geoserver/rete/wfs?service=WFS&version=1.0.0&request=DescribeFeatureType&typeName=rete%3Apoi_rete"
      })
});
map.addLayer(highlightLayer);
selectFeatureControl = new OpenLayers.Control.SelectFeature(highlightLayer);
map.addControl(selectFeatureControl);
selectFeatureControl.activate();

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
               console.log(id_poi);
               arr.push(id_poi);
            }
            $.ajax({
                 url: 'inc/popupPoi.php',
                 type: 'POST',
                 data: {arr:arr},
                 success: function(data){
                   $("#result").fadeIn('fast');
                   $("#resultContent").html(data);
                   var resDivH = $("#result").outerHeight(true);
                   var resHeaderH = $("#resultHeader").outerHeight(true);
                   $("#resultContent").height(resDivH - resHeaderH -20);
                   $('.trigger').on({
                     click: function(){
                      var ll = $(this).attr('ll');
                      if (ll == 0) return;
                      var parse = ll.split(',');
                      var newll= new OpenLayers.LonLat(parse[0],parse[1]);
                      map.setCenter(newll,18);
                     },
                     mouseenter: function() {
                      var id_poi=$(this).data('id');
                      for(var f=0;f<highlightLayer.features.length;f++) {
                       if(highlightLayer.features[f].attributes.id_poi == id_poi) {
                         selectFeatureControl.select(highlightLayer.features[f]);
                       }
                      }
                     },
                     mouseleave: function() {
                      var id_poi=$(this).data('id');
                      featHiLite = highlightLayer.getFeaturesByAttribute('id_poi', id_poi);
                      highlightLayer.drawFeature(featHiLite[0], "default");
                     }
                   });
                 }
            });
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

$('.olControlZoom').append('<a href="#" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>');
$('.olControlZoomIn').attr("title","Ingrandisci la mappa");
$('.olControlZoomOut').attr("title","Diminuisci la mappa");
$("#max").click(function(){map.zoomToExtent(extent);});
$("select[name=zoomComune]").change(function(){
    var v = $(this).val();
    var parse = v.split(',');
    var newExt= new OpenLayers.Bounds(parse[0],parse[1],parse[2],parse[3]);
    map.zoomToExtent(newExt,18);
});
} //end init mappa
