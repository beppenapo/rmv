<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8" />
<meta name="generator" content="gedit" >
<meta name="author" content="Giuseppe Naponiello" >
<meta name="robots" content="INDEX,FOLLOW" />
<meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="css/mappa_test.css" rel="stylesheet" media="screen" />

<title>Rete Museale del Valdarno di sotto</title>

<style>

</style>

</head>
<body onload="init()">
<header id="head"><?php require_once('inc/head.php')?></header>
<div id="map">
 <div id="menuMap">
  <h1 id="menuMapIco" class="cursor"><i class="fa fa-bars"></i></h1>
  <div id="switcher">
   <div id="switcherWrap">
    <h2 id="baseHeader">Sfondi</h2>
    <ul>
     <li><label class="layerActive"><input type="radio" class="baselayers" name="layer" id="mapquest" value="baseOSM" onclick="map.setBaseLayer(baseOSM)" checked />OpenStreetMap</label></li>
     <li><label><input type="radio" class="baselayers" name="layer" id="gsat" value="gsat" onclick="map.setBaseLayer(gsat)" />Satellite</label></li>
    </ul>
   
    <h2>Punti di interesse</h2>
    <ul id="overlayList">
     <li>
      <label for="areearch" class="layerActive">
       <img src="img/ico/nuove/aree_archeologiche.png" class="switcherIco"/>
       Aree archeologiche
       <input type="checkbox" id="areearch" class="overlayers" name="tipologia" value="1" checked>
      </label> 
     </li>
     <li>
      
      <label for="icomusei" class="layerActive">
       <img src="img/ico/nuove/musei.png" class="switcherIco"/>
       Musei e raccolte
       <input type="checkbox" id="icomusei" class="overlayers" name="tipologia" value="2" checked>
      </label> 
     </li>
     <li>
      <label for="icopalazzo" class="layerActive">
       <img src="img/ico/nuove/edifici_civili.png" class="switcherIco"/>
       Edifici civili
       <input type="checkbox" id="icopalazzo" class="overlayers" name="tipologia" value="3" checked>
      </label> 
     </li>
     <li>
      <label for="icoattpro" class="layerActive">
       <img src="img/ico/nuove/att_produttive.png" class="switcherIco"/>
       Attività produttive
       <input type="checkbox" id="icoattpro" class="overlayers" name="tipologia" value="4" checked>
      </label> 
     </li>
     <li>
      <label for="icostrdif" class="layerActive">
       <img src="img/ico/nuove/strutt_difensive.png" class="switcherIco"/>
       Strutture difensive
       <input type="checkbox" id="icostrdif" class="overlayers" name="tipologia" value="5" checked>
      </label> 
     </li>
     <li>
      <label for="icochiesa" class="layerActive">
       <img src="img/ico/nuove/edifici_religiosi.png" class="switcherIco"/>
       Edifici religiosi
       <input type="checkbox" id="icochiesa" class="overlayers" name="tipologia" value="6" checked>
      </label> 
     </li>
     <li>
      <label for="icocisterna" class="layerActive">
       <img src="img/ico/nuove/fonti_idriche.png" class="switcherIco"/>
       Strutture idriche
       <input type="checkbox" id="icocisterna" class="overlayers" name="tipologia" value="7" checked>
      </label> 
     </li>
     <li>
      <label for="icond" class="layerActive">
       <img src="img/ico/nuove/altre_tipologie.png" class="switcherIco"/>
       Altre tipologie
       <input type="checkbox" id="icond" class="overlayers" name="tipologia" value="8" checked>
      </label> 
     </li>
    </ul>
   </div>
  </div>
 </div>
 
 <div id="result">
  <div id="resultHeader" class="cursor"><i class="fa fa-times"></i></div>
  <div id="resultContent">
 
  </div>
 </div>
</div> <!-- map -->

<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script src="http://maps.google.com/maps/api/js?v=3.2&amp;sensor=false"></script>
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript"  src="js/map.js"></script>
</body>
</html>
