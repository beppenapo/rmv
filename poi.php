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
, sito.id_icone
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
 .mapTitle {position: absolute;top: 0px;left: 0px;width: 90%; margin: 0px auto; padding: 1% 5%; background-color: rgba(17,17,17,0.5);  z-index: 3000;}
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
 #mainContentWrap article{line-height:1.8em;height:auto !important;position:relative;}
 #mainContentWrap i{font-size:0.8em;}
 #galleryDiv{display:none;position:absolute;left:0;right:0;height:100%;background-color:rgba(0,0,0,0.7);z-index:2000;}
 #galleryWrap{position:relative;margin:2% auto 0;}
 #galleryWrap header{position:relative;width:99%;background:#fff;margin:0px;padding:0.5%;text-align:right;}
 #caption{position: relative;display: inline-block;background: #fff;width: 98%;padding: 1%;}
 .myDialog form{ margin: 20px auto; position: relative; width: 80%;}
 .myDialog input, .myDialog select, .myDialog textarea, .myDialog button{width:100% !important; margin:0px auto 10px;padding: 5px; border-radius: 5px; border: 1px solid grey;}
 input, button{cursor:pointer;}
 #divFotoDialog textarea{height:100px;}
 #updateDescriz textarea{min-height:200px; height:auto; max-height:400px;}
 .msg{margin:10px 0px;}
</style>
</head>
<body onload="init()">
 <header id="head"><?php require_once('inc/head.php'); ?></header>
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
   <a href="#" id="modDescr" class="button"><i class="fa fa-file-text-o fa-fw"></i> modifica descrizione</a>
   <a href="#" id="modInfo" class="button"><i class="fa fa-thumb-tack fa-fw"></i> modifica info</a>
   <a href="#" id="modFoto" class="button"><i class="fa fa-picture-o fa-fw"></i> aggiungi foto</a>
  <?php } ?>
   <a href="#" id="print" class="button"><i class="fa fa-file-pdf-o fa-fw"></i> Stampa</a>
   <section>
    <header id="descrizione"><span lang="it"><i class="fa fa-file-text-o"></i> Descrizione</span></header>
    <article id="DescrizioneContent"><?php echo(nl2br($arr['descrizione'])); ?></article>
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
       while($foto = pg_fetch_array($imgexec)){
        $descrizione = str_replace("\"", "''",$foto['descr_foto']);
        echo "<div class='wrapThumb'>";
        echo "<div class='photoTool'>";
            echo "<a href='#' class='viewThumb photoButton' data-id='".$foto['id_foto']."' title='Ingrandisci l&#39;immagine'><i class='fa fa-eye'></i></a>";
        if($_SESSION['id_user']){
            echo '<a href="#" class="photoButton upPhoto" data-dida="'.$descrizione.'" data-id="'.$foto['id_foto'].'" title="Modifica la didascalia della foto"><i class="fa fa-cog fa-2x"></i></a>';
            echo "<a href='#' class='photoButton delPhoto' data-path='".$foto['percorso_foto']."' data-id='".$foto['id_foto']."' title='Elimina la foto'><i class='fa fa-times fa-2x'></i></a>";
        }
        echo "</div>";
        echo '<img src="foto/'.$foto['percorso_foto'].'" alt="'.$descrizione.'" class="thumb" title="'.$descrizione.'">';
        echo "</div>";
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
 <footer><?php require_once("inc/footer.php"); ?></footer>



<div id="galleryDiv">
 <div id="galleryWrap">
  <header><i class="fa fa-times cursor"></i></header>
  <div id="fotoContent"></div>
  <span id="caption"></span>
 </div>
</div>

<div class="myDialog" id="updateDescriz">
    <div class="myDialogWrapContent">
        <div class="myDialogContent" style="height:350px !important;">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain">
                <form>
                    <textarea name="newDescr"><?php echo $arr['descrizione']; ?></textarea>
                    <span class="msg" id="msgDescr"></span>
                    <button type="button" name="upDescriz">Aggiorna dati</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="myDialog" id="updateInfo">
    <div class="myDialogWrapContent">
        <div class="myDialogContent">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain">
                <form>
                    <h1 style="margin-bottom:10px;">I campi con l'asterisco (*) sono obbligatori e vanno compilati</h1>
                    <div>
                        <label>inv/nctn</label>
                        <textarea name="inv"><?php echo($inv); ?></textarea>
                    </div>
                    <div>
                        <label>* nome sito</label>
                        <textarea name="nome" class="obbligatorio"><?php echo $arr['sito_nome']; ?></textarea>
                    </div>
                    <div>
                        <label>* Comune</label>
                        <input class="autocompletamento obbligatorio" id="comuneList" value="<?php echo $arr['comune']; ?>" data-campo="comune" type="search">
                        <input type="hidden" value="<?php echo $arr['id_comune']; ?>" id="comune" name="comune">
                    </div>
                    <div>
                        <label>località</label>
                        <select name="localita">
                            <?php
                            $lq = "select * from liste.localita where id_comune = ".$arr['id_comune']." or id_localita = 15 order by localita asc;";
                            $le = pg_query($connection,$lq);
                            while($l = pg_fetch_array($le)){
                                $sel = ($l['id_localita']==$arr['id_localita'])?'selected':'';
                                echo "<option value='".$l['id_localita']."' ".$sel.">".$l['localita']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>toponimo</label>
                        <select name="toponimo">
                            <?php
                            $tq = "select * from liste.toponimo where id_localita = ".$arr['id_localita']." or id_toponimo = 12 order by toponimo asc;";
                            $te = pg_query($connection,$tq);
                            while($t = pg_fetch_array($te)){
                                $sel = ($t['id_toponimo']==$arr['id_toponimo'])?'selected':'';
                                echo "<option value='".$t['id_toponimo']."' ".$sel.">".$t['toponimo']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>microtoponimo</label>
                        <select name="microtoponimo">
                            <?php
                            $mq = "select * from liste.microtoponimo where id_toponimo = ".$arr['id_toponimo']." or id_microtoponimo = 1 order by microtoponimo asc;";
                            $me = pg_query($connection,$mq);
                            while($m = pg_fetch_array($me)){
                                $sel = ($t['id_microtoponimo']==$arr['id_microtoponimo'])?'selected':'';
                                echo "<option value='".$m['id_microtoponimo']."' ".$sel.">".$m['microtoponimo']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>posizione</label>
                        <textarea name="posizione" id="posizione" class="mediumDesc"><?php echo($arr['posizione']); ?></textarea>
                    </div>
                    <div>
                        <label>* tipologia sito</label>
                        <select name="tipoSito" id="tipoSito" class="obbligatorio">
                           <?php
                            $q1=("SELECT * FROM liste.sito_tipo;");
                            $ex1 = pg_query($connection, $q1);
                            while($tipo = pg_fetch_array($ex1)){
                                $sel = ($tipo['id_sito_tipo']==$arr['id_sito_tipo'])?'selected':'';
                                echo "<option value='".$tipo['id_sito_tipo']."' ".$sel.">".$tipo['tipo']."</option>";
                            }
                           ?>
                        </select>
                    </div>
                    <div>
                        <label>* periodo</label>
                        <select name="periodo" id="periodo" class="obbligatorio">
                                <?php
                                $q2=("SELECT * FROM liste.periodo_cultura order by id_periodo_cultura asc ;");
                                $ex2 = pg_query($connection, $q2);
                                while($p = pg_fetch_array($ex2)){
                                    $sel = ($p['id_periodo_cultura']==$arr['id_periodo'])?'selected':'';
                                    echo "<option value='".$p['id_periodo_cultura']."' ".$sel.">".$p['periodo_cultura']."</option>";
                                }
                                ?>
                        </select>
                    </div>
                    <div>
                        <label>cronologia iniziale</label>
                        <textarea name="cronoi"><?php echo($arr['crono_iniziale']); ?></textarea>
                    </div>
                    <div>
                        <label>cronologia finale</label>
                        <textarea name="cronof"><?php echo($arr['crono_finale']); ?></textarea>
                    </div>
                    <div>
                        <label>funzionario</label>
                        <textarea name="funzionario" id="funzionario"><?php echo($arr['funzionario']); ?></textarea>
                    </div>
                    <div>
                        <label>* accessibilità</label>
                        <select name="accessibilita" class="obbligatorio">
                            <?php
                            $q3=("SELECT * FROM liste.accessibilita order by accessibilita asc;");
                            $ex3 = pg_query($connection, $q3);
                            while($acc = pg_fetch_array($ex3)){
                                $sel = ($acc['id_accessibilita']==$arr['id_accessibilita'])?'selected':'';
                                echo "<option value='".$acc['id_accessibilita']."' ".$sel.">".$acc['accessibilita']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>* definizione generale</label>
                        <select name="def_gen" class="obbligatorio">
                            <?php
                            $q4=("SELECT * FROM liste.definizione_generale order by id_def_generale;");
                            $ex4 = pg_query($connection, $q4);
                            while($dg = pg_fetch_array($ex4)){
                                $sel = ($dg['id_def_generale']==$arr['id_def_generale'])?'selected':'';
                                echo "<option value='".$dg['id_def_generale']."' data-ico='".$dg['ico']."' ".$sel.">".$dg['def_generale']."</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="ico" value="<?php echo $arr['id_icone']; ?>">
                    </div>
                    <div>
                        <label>definizione specifica</label>
                        <select name="def_spec">
                            <?php
                            $dsq= "SELECT * FROM liste.definizione_specifica where id_def_generale = ".$arr['id_def_generale']." order by def_specifica asc;";
                            $dse = pg_query($connection, $dsq);
                            while($ds = pg_fetch_array($dse)){
                                $sel = ($ds['id_def_specifica']==$arr['id_def_specifica'])?'selected':'';
                                echo "<option value='".$ds['id_def_specifica']."' ".$sel.">".$ds['def_specifica']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>* stato di conservazione</label>
                        <select name="conservazione" class="obbligatorio">
                            <option value=""></option>
                            <?php
                            $q5=("SELECT * FROM liste.stato_conservazione order by id_stato_conservazione asc;");
                            $ex5 = pg_query($connection, $q5);
                            while($sc = pg_fetch_array($ex5)){
                                $sel = ($sc['id_stato_conservazione']==$arr['id_stato_conservazione'])?'selected':'';
                                echo "<option value='".$sc['id_stato_conservazione']."' ".$sel.">".$sc['stato_conservazione']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>materiale</label>
                        <select name="materiale">
                            <?php
                            $q6=("SELECT * FROM liste.materiale order by materiale asc;");
                            $ex6 = pg_query($connection, $q6);
                            while($mat = pg_fetch_array($ex6)){
                                $sel = ($mat['id_materiale']==$arr['id_materiale'])?'selected':'';
                                echo "<option value='".$mat['id_materiale']."' ".$sel.">".$mat['materiale']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>tecnica</label>
                        <select name="tecnica">
                            <?php
                            $tecq="SELECT * FROM liste.tecnica where id_materiale = ".$arr['id_materiale']." or id_tecnica = 27 order by tecnica asc;";
                            $tece = pg_query($connection, $tecq);
                            while($tec = pg_fetch_array($tece)){
                                $sel = ($tec['id_tecnica']==$arr['id_tecnica'])?'selected':'';
                                echo "<option value='".$tec['id_tecnica']."' ".$sel.">".$tec['tecnica']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>contatti</label>
                        <textarea name="contatti" class="longDesc"><?php echo $arr['contatti']; ?></textarea>
                    </div>
                    <div>
                        <label>risorsa web</label>
                        <input type="url" name="link" value="<?php echo $arr['link']; ?>">
                    </div>
                    <div>
                        <label>note</label>
                        <textarea name="note" class="longDesc"><?php echo $arr['note']; ?></textarea>
                    </div>
                    <span class="msg" id="msgInfo"></span>
                    <button type="button" name="upInfo">Aggiorna dati</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="myDialog" id="divFotoDialog">
    <div class="myDialogWrapContent">
        <div class="myDialogContent" style="height:300px !important;">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain">
                <form action="upload_file.php" method="post" enctype="multipart/form-data" id="formFoto">
                    <input type="hidden" name="poiFoto" value="<?php echo($id);?>" />
                    <input type="file" name="file" id="file">
                    <textarea name="descrFoto" placeholder="Inserisci una breve descrizione dell'immagine"></textarea>
                    <input type="submit" name="submit" value="Carica foto">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="myDialog" id="delFoto">
    <div class="myDialogWrapContent">
        <div class="myDialogContent" style="height:200px !important;">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain">
                <form>
                    <input type="hidden" name="foto" value="" />
                    <span class="msg" id="msgDelFoto">Attenzione, stai per eliminare una foto dal server, l'operazione non può essere annullata.</span>
                    <button type="button" name="delFotoButt">Ok, elimina foto</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="myDialog" id="upDidascalia">
    <div class="myDialogWrapContent">
        <div class="myDialogContent" style="height:300px !important;">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain">
                <form>
                    <input type="hidden" name="foto" value="" />
                    <textarea name="didascalia" style="height:100px;"></textarea>
                    <span class="msg" id="msgDidascalia"></span>
                    <button type="button" name="didascaliaButt">Aggiorna didascalia</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="myDialog" id="delPoiForm">
    <div class="myDialogWrapContent">
        <div class="myDialogContent">
            <div class="myDialogContentHeader"><i class="fa fa-times"></i></div>
            <div class="myDialogContentMain">
                <form>
                    <h1 class="warning">Attenzione, stai per eliminare un punto di interesse!</h1>
                    <p>Se decidi di cancellare una scheda, eliminerai definitivamente anche tutti i dati ad esso associati, comprese foto e immagini caricate sul server.</p>
                    <p id="delMsg"></p>
                    <button type="button" name="delButton">Ok, elimina la scheda</button>
                    <button type="button" name="noDelButton">No, annulla operazione</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jq-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script>
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript" src="js/dinSelect.js"></script>
<script type="text/javascript">
var idPoi = <?php echo $id; ?>;
$(document).ready(function() {

    $.fn.resize=function(a){
        var d=Math.ceil;
        if(a==null)a=200;
        var e=a, f=a;
        $(this).each(function(){
            var b=$(this).height(),c=$(this).width();
            if(b>c)f=d(c/b*a);
            else e=d(b/c*a);
            $(this).css({height:e,width:f});
        })
    };
    var thumbWidth = $(".wrapThumb").width();
    $('.wrapThumb img').resize(thumbWidth);

    $("a.button, a.photoButton").click(function(e){e.preventDefault();})
    $(".myDialogContentHeader i").click(function(){$(".myDialog").fadeOut('fast'); scroll();});
    var ratio = 0;
    var widthDef;
    var heightDef;
    $('.thumb').css({width:'100%'});
    $(".viewThumb").click(function(){var foto = $(this).parent().next('img'); maximFoto(foto); });
    $('.thumb').click(function(){ var foto = $(this); maximFoto(foto); });

    $('.delPhoto').on("click", function(){
        var foto = $(this).data('id');
        var path = $(this).data('path');
        $("#delFoto input[name=foto]").val(foto);
        $('#delFoto').fadeIn('fast', function(){noScroll();});
        $("button[name=delFotoButt]").click(function(){
            $.ajax({
                url: 'inc/delFoto.php',
                type: 'POST',
                data: {foto:foto, path:path},
                success: function(data){
                    $("#msgDelFoto").text(data);
                    $("#delFoto").delay(3000).fadeOut('fast', function(){location.reload();});
                }
            }); //fine ajax
        });

    });

    $('.upPhoto').on("click", function(){
        var foto = $(this).data('id');
        var didascalia = $(this).data('dida');
        $("#upDidascalia input[name=foto]").val(foto);
        $("#upDidascalia textarea[name=didascalia]").val(didascalia);
        $('#upDidascalia').fadeIn('fast', function(){noScroll();});
        $("button[name=didascaliaButt]").click(function(){
            var didascalia = $("#upDidascalia textarea[name=didascalia]").val();
            $.ajax({
                url: 'inc/updateFoto.php',
                type: 'POST',
                data: {foto:foto, didascalia:didascalia},
                success: function(data){
                    $("#msgDidascalia").text(data);
                    $("#upDidascalia").delay(3000).fadeOut('fast', function(){location.reload();});
                }
            });
        });

    });

    $( ".photoTool a" ).on({
        mouseenter: function() {$(this).parent('div').css("background","rgba(0,0,0,0.8)").addClass("transition");},
        mouseleave: function() {$(this).parent('div').css("background","rgba(0,0,0,0.3)").removeClass("transition");}
    });


    $("#modDescr").click(function(){
        $('#updateDescriz').fadeIn('fast', function(){noScroll();});
        $("button[name=upDescriz]").click(function(){
            var newDescr = $("textarea[name=newDescr]").val();
            if(!newDescr){$("#msgDescr").text('Devi inserire una descrizione'); return false;}
            $.ajax({
                url: 'inc/updateDescr.php',
                type: 'POST',
                data: {id:idPoi, descr:newDescr},
                success: function(data){
                    var txt = newDescr.replace(/\n/g,"<br>");
                    $("#DescrizioneContent").html(txt);
                    $("#msgDescr").text(data);
                    $("#updateDescriz").delay(3000).fadeOut('fast', function(){$("#msgDescr").text('');scroll();});
                }
            }); //fine ajax
        });
    });
    $('#modInfo').click(function(){
        $('#updateInfo').fadeIn('fast', function(){noScroll();});
        $("button[name=upInfo]").click(function(){
            var inv = $("textarea[name=inv]").val();
            var nome = $("textarea[name=nome]").val();
            var comune = $("input[name=comune]").val();
            var localita = $("select[name=localita]").val();
            var toponimo = $("select[name=toponimo]").val();
            var microtoponimo = $("select[name=microtoponimo]").val();
            var posizione = $("textarea[name=posizione]").val();
            var tipologia = $("select[name=tipoSito]").val();
            var icona = $("input[name=icona]").val();
            var periodo = $("select[name=periodo]").val();
            var cronoiniz = $("textarea[name=cronoi]").val();
            var cronofin = $("textarea[name=cronof]").val();
            var funzionario = $("textarea[name=funzionario]").val();
            var accessibilita = $("select[name=accessibilita]").val();
            var defgen = $("select[name=def_gen]").val();
            var icona = $("input[name=ico]").val();
            var defspec = $("select[name=def_spec]").val();
            var conservazione = $("select[name=conservazione]").val();
            var materiale = $("select[name=materiale]").val();
            var tecnica = $("select[name=tecnica]").val();
            var contatti = $("textarea[name=contatti]").val();
            var link = $("input[name=link]").val();
            var note = $("textarea[name=note]").val();
            //console.log("inv: "+inv+"\nnome: "+nome+"\ncomune: "+comune+"\nlocalita: "+localita+"\ntoponimo: "+toponimo+"\nmicrotoponimo: "+microtoponimo+"\nposizione: "+posizione+"\ntipologia: "+tipologia+"\nperiodo: "+periodo+"\nci: "+cronoiniz+"\ncronofin: "+cronofin+"\nfunzionario: "+funzionario+"\naccessibilita: "+accessibilita+"\ndefgen: "+defgen+"\nico: "+icona+"\ndefspec: "+defspec+"\nconservazione: "+conservazione+"\nmateriale: "+materiale+"\ntecnica: "+tecnica+"\ncontatti: "+contatti+"\nlink: "+link+"\nnote: "+note); return false;
            if(!nome){
                $("textarea[name=nome]").addClass('errorClass');
                $("#msgInfo").text('I campi in rosso sono obbligatori e vanno compilati');
            }else{
                $("textarea[name=nome]").removeClass('errorClass');
                $("#msgInfo").text('');
                var data = {id:idPoi, inv:inv, nome:nome, comune:comune, localita:localita, toponimo:toponimo, microtoponimo:microtoponimo, posizione:posizione, tipologia:tipologia, periodo:periodo, cronoiniz:cronoiniz, cronofin:cronofin, funzionario:funzionario, accessibilita:accessibilita, defgen:defgen, icona:icona, defspec:defspec, conservazione:conservazione, materiale:materiale, tecnica:tecnica, contatti:contatti, link:link, note:note};
                $.ajax({
                    url: 'inc/updateInfo.php',
                    type: 'POST',
                    data: data,
                    success: function(data){
                        $("#msgInfo").text(data).delay(3000).fadeOut('fast', function(){location.reload();});
                    }
                }); //fine ajax
            }
        });
    });
    $('#modFoto').click(function(){ $('#divFotoDialog').fadeIn('fast'); });

    $("#delPoiLink").on("click", function(){
      noScroll();
      var i = $(this).data('id');
      $("#delPoiForm").fadeIn('fast');
      $(".myDialogContent").css("height","auto");
      $("button[name=noDelButton]").on("click", function(){ $(".myDialog").fadeOut('fast', function(){scroll();}); });
      $("button[name=delButton]").on("click", function(){
        $.ajax({
            url: 'inc/delPoi.php',
            type: 'POST',
            data: {id:i},
            success: function(data){
                $("#delMsg").html(data).delay(3000).fadeOut('fast', function(){window.location.href="poiList.php";});
            }
        });
      });
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
   maxExtent: new OpenLayers.Bounds (-20037508.34,-20037508.34,20037508.34,20037508.34),
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
map2.setCenter(newll2,18);

$("#print").click(function(){print();map.updateSize();});

} //end init mappa
</script>
</body>
</html>
