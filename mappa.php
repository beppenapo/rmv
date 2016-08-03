<?php
require("inc/db.php");
//select comuni
$a='SELECT c.gid, c.nome, st_extent(c.the_geom) as ext FROM sito, comuni c WHERE sito.id_comune = c.gid group by c.gid, c.nome ,c.the_geom';
$aa=pg_query($connection, $a);
$comuni = "<option value='1160225,5397418,1244871,5441706'>tutti i Comuni</option>";
while($aList = pg_fetch_array($aa)){
    $ext = str_replace(" ",",",substr($aList[ext],4,-1));
    $comuni .= "<option value='".str_replace(" ",",",substr($aList[ext],4,-1))."'>".$aList[nome]."</option>";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require("inc/meta.php"); ?>
    <link href="css/mappa.css" rel="stylesheet" media="screen" />
</head>
<body onload="init()">
    <header id="head"><?php require_once('inc/head.php');?></header>
    <div id="map">
        <div id="mapTool">
            <label>zoom su: </label><select name="zoomComune"><?php echo $comuni; ?></select>
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
            <div id="resultContent"></div>
        </div>
    </div>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
    <script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
    <script type="text/javascript" src="js/openlayers/lib/OpenLayers.js"></script>
    <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyAjIFKh5283gkT3TEdbrjxzm1-sFQppG1Y" type="text/javascript"></script>
    <script type="text/javascript" src="js/func.js"></script>
    <script type="text/javascript"  src="js/map.js"></script>
</body>
</html>
