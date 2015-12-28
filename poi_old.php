<?php
session_start();
require_once("inc/db.php");
$id=$_GET['id'];
$poi="SELECT sito.id, sito.inv, sito.sito, sito.posizione, sito.descrizione, sito.crono_iniziale, sito.crono_finale, sito.note, sito.funzionario, sito.data_compilazione, sito.id_accessibilita, accessibilita.accessibilita, sito.id_def_generale, definizione_generale.def_generale, sito.id_def_specifica, definizione_specifica.def_specifica,sito.id_materiale, materiale.materiale, sito.id_microtoponimo, microtoponimo.microtoponimo,sito.id_sito_tipo, sito_tipo.tipo, sito.id_stato_conservazione, stato_conservazione.stato_conservazione,sito.id_tecnica, tecnica.tecnica, sito.id_toponimo, toponimo.toponimo, sito.sito_nome, sito.id_localita, localita.localita, sito.id_comune, comuni.nome AS comune, province.nome AS provincia,sito.id_periodo,  periodo_cultura.periodo_cultura, sito.id_compilatore, usr.cognome ||' '|| usr.nome as compilatore,sito.contatti,  st_x(st_transform(sito.the_geom, 4326)) AS lon, st_y(st_transform(sito.the_geom, 4326)) AS lat
   FROM sito, liste.accessibilita, liste.definizione_generale, liste.definizione_specifica, liste.materiale, liste.microtoponimo, liste.sito_tipo, liste.stato_conservazione, liste.tecnica, liste.toponimo, liste.localita, comuni, province, liste.periodo_cultura, usr
  WHERE sito.id_accessibilita = accessibilita.id_accessibilita AND sito.id_def_generale = definizione_generale.id_def_generale AND sito.id_def_specifica = definizione_specifica.id_def_specifica AND sito.id_materiale = materiale.id_materiale AND sito.id_microtoponimo = microtoponimo.id_microtoponimo AND sito.id_sito_tipo = sito_tipo.id_sito_tipo AND sito.id_stato_conservazione = stato_conservazione.id_stato_conservazione AND sito.id_tecnica = tecnica.id_tecnica AND sito.id_toponimo = toponimo.id_toponimo AND sito.id_localita = localita.id_localita AND sito.id_periodo = periodo_cultura.id_periodo_cultura AND sito.id_comune = comuni.gid AND comuni.id_provinc::text = province.id_provinc::text and sito.id_compilatore = usr.id and sito.id = $id";
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
$lon=$arr['lon'];
$lat=$arr['lat'];
$lon2 = substr($lon,0,5);
$lat2 = substr($lat,0,5);
$h1 = $arr['sito_nome'].' (lonlat: '.$lon2.', '.$lat2.')';
if(isset($arr['inv']) && isset($arr['nctn'])){$inv = $arr['inv'].' / '.$arr['nctn'];}
if(!isset($arr['inv']) && !isset($arr['nctn'])){$inv = '';}
if(isset($arr['inv'])){$inv = $arr['inv'];}
if(isset($arr['nctn'])){$inv = $arr['nctn'];}
$localita=($arr['localita']=='non determinabile')?'':$arr['localita'];
$toponimo=($arr['toponimo']=='non determinabile')?'':$arr['toponimo'];
$microtoponimo=($arr['microtoponimo']=='non determinabile')?'':$arr['microtoponimo'];
$tipo=($arr['tipo']=='non determinbile')?'':$arr['tipo'];
$tecnica=($arr['tecnica']=='non determinabile')?'':$arr['tecnica'];
$materiale=($arr['materiale']=='non determinabile')?'':$arr['materiale'];
$conservazione=($arr['stato_conservazione']=='non determinabile')?'':$arr['stato_conservazione'];
$periodo = ($arr['periodo_cultura'] == 'Non determinabile')?'':$arr['periodo_cultura'];
$def_gen = ($arr['def_generale'] == 'non determinabile')?'':$arr['def_generale'];
$def_spec = ($arr['def_specifica'] == 'non determinabile')?'':$arr['def_specifica'];
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
   <link href="css/default.css" rel="stylesheet" media="screen" />
   <link href="css/banner.css" rel="stylesheet" media="screen" />
   <link href="css/tooltip.css" rel="stylesheet" media="screen" />
   <link href="js/jq-ui/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet" media="screen" />
   <link href="js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
<title>Rete Museale del Valdarno di sotto</title>
<style>
#map{display:block;position:relative;margin:10px auto;padding:0px;width:79%;height:50%;box-shadow: 5px 5px 10px #aaa;}
#datipoi{height:100% !important;}
#datipoi article, #news article{overflow: auto;height: auto !important;margin: 4% auto;font-size: 0.8em;width: 90%;}
#news article{line-height:1.5em;}
#datipoi table{width:100%;}
#datipoi td {padding:1% 2%;}
#datipoi td:nth-child(1) {width:50%;}
#mapTitle{position:absolute;top:0px;left:0px;width:90%;margin:0px auto;padding:1% 5%;background-color:rgba(17,17,17,0.7);z-index:3000;}
#mapTitle h1{font-size:1.3em;color:#fff;text-align:center;}
div.olControlZoom{top:60px !important;}
#delPoiDiv h1, #delPoiDiv h2{margin:20px 10px;}
#delPoiDiv h2{font-size:90%}

#wrapForm{width:100%;height:89%;overflow:auto;font-size: 0.7em;}
#modPoiDiv table, #divFotoDialog{width:95% !important; margin:10px auto;}
#modPoiDiv table td{vertical-align:middle;padding:5px 0px;}
#modPoiDiv table td:first-child{width:30%;text-align:right;padding-right:30px;}
#modPoiDiv textarea, #modPoiDiv input{height: 15px;}
#modPoiDiv textarea,#modPoiDiv select,#modPoiDiv input{width:95% !important;padding: 10px;}
#modPoiDiv select{margin: 0;background: #fff;outline: none;display: inline-block;cursor: pointer;-webkit-appearance: none;-moz-appearance: none;text-indent: 0.01px;}

#formFoto{margin:15px 0px;}
#formFoto input,#formFoto textarea{width:95%;}
#formFoto textarea{padding:5px; height:100px;}
#divFotoDialog{font-size:0.8em; }
#fotoGallery img{cursor:pointer;z-index:1;}

.mediumDesc{height:50px !important;}
.longDesc{height:100px !important;}

.ui-autocomplete{z-index:30001 !important;}
.ui-menu .ui-menu-item a{background:none;font-size:0.75em !important;}
.ui-menu .ui-menu-item a:hover{border:none !important;margin:0px !important; background-color:#d6d6d6;}
article{padding:0px 3%;}
.wrapThumb{float:left;width:200px;height:132px;margin:2px;}
</style>
</head>
<body onload="init()">
 <header id="head">
  <?php require_once('inc/header.php')?>
 </header>
   <section id="map">
   <div id="mapTitle"><h1><?php echo($h1); ?></h1></div>
  </section>
  
<div id="wrap">
 <div id="colLeft">
<section>
   <div id="descrizione" style="width:97% !important;">
     <h1>Descrizione</h1>
     <article>
      <?php 
        echo(nl2br($arr['descrizione'])); 
        if(isset($_SESSION['id_user'])){
      ?>
      <div class='wrapModLink'><a href="#" class="modLink" id="modDescr"> modifica descrizione</a></div>
      <?php } ?>
     </article>
   </div>
  </section>
  <section>
   <div id='fotoGallery' style="width:97% !important;" >
   <h1>Galleria fotografica</h1>
    <article>
     <div style="width:100%;height:auto;display:inline-block;">
      <?php 
         $imgq = ("select * from foto_poi where id_poi = $id;");
         $imgexec = pg_query($connection, $imgq);
         $imgrow = pg_num_rows($imgexec);
         while($foto = pg_fetch_array($imgexec, NULL, PGSQL_ASSOC)){
          echo "<div class='wrapThumb' data-id='".$foto['id_foto']."'><img src='foto/".$foto['percorso_foto']."' alt='".$foto['descr_foto']."' title='".$foto['descr_foto']."' class='thumb'/></div>";
         }
      ?>
      </div>
      <?php if(isset($_SESSION['id_user'])){?>
      <div class='wrapModLink'><a href="#" class="modLink" id="modFoto">aggiungi foto</a></div>
      <div id="divFotoDialog" class="hidden">
       <form action="inc/upload_file.php" method="post" enctype="multipart/form-data" id="formFoto">
        <input type="hidden" name="poiFoto" value="<?php echo($id);?>" />
        <input type="file" name="file" id="file">
        <textarea name="descrFoto" placeholder="Inserisci una breve descrizione dell'immagine"></textarea>
        <input type="submit" name="submit" value="Carica foto">
        <input class="closeDialog" type="button" name="chiudiFoto" value="chiudi finestra" />
       </form>
      </div>
      <?php } ?>
    </article>
   </div>
 </section>
  <div id="colLeft1">
   <div id="datipoi">
    <section>
     <h1>Info sito</h1>
     <article>
      <table class="zebra">
       <?php if(isset($_SESSION['id_user'])){?><tr><td width="50%">Inv./Nctn : </td><td><?php echo($inv); ?></td></tr><?php } ?>
       <tr><td>Provincia: </td><td><?php echo($arr['provincia']); ?></td></tr>
       <tr><td>Comune: </td><td><?php echo($arr['comune']); ?></td></tr>
       <tr><td>Località: </td><td><?php echo($localita); ?></td></tr>
       <tr><td>Toponimo: </td><td><?php echo($toponimo); ?></td></tr>
       <tr><td>Microtoponimo: </td><td><?php echo($microtoponimo); ?></td></tr>
       <tr><td>Posizione: </td><td><?php echo($arr['posizione']); ?></td></tr>
       <tr><td>Accessibilità: </td><td><?php echo($arr['accessibilita']); ?></td></tr>
       <?php if(isset($_SESSION['id_user'])){?><tr><td>Tipo: </td><td><?php echo($tipo); ?></td></tr><?php } ?>
       <tr><td>Definizione generale: </td><td><?php echo($def_gen); ?></td></tr>
       <tr><td>Definizione specifica: </td><td><?php echo($def_spec); ?></td></tr>
       <tr><td>Periodo/Cultura: </td><td><?php echo($periodo); ?></td></tr>
       <tr><td>Cronologia iniziale: </td><td><?php echo($arr['crono_iniziale']); ?></td></tr>
       <tr><td>Cronologia finale: </td><td><?php echo($arr['crono_finale']); ?></td></tr>
       <tr><td>Tecnica: </td><td><?php echo($tecnica); ?></td></tr>
       <tr><td>Materiale: </td><td><?php echo($materiale); ?></td></tr>
       <tr><td>Stato conservazione: </td><td><?php echo($conservazione); ?></td></tr>
       <?php if(isset($_SESSION['id_user'])){?><tr><td>Note: </td><td><?php echo(nl2br($arr['note'])); ?></td></tr><?php } ?>
       <?php if(isset($_SESSION['id_user'])){?><tr><td>Funzionario: </td><td><?php echo($arr['funzionario']); ?></td></tr><?php } ?>
       <?php if(isset($_SESSION['id_user'])){?><tr><td>Compilatore: </td><td><?php echo($arr['compilatore']); ?></td></tr><?php } ?>
       <?php if(isset($_SESSION['id_user'])){?><tr><td>Data compilazione: </td><td><?php echo($arr['data_compilazione']); ?></td></tr><?php } ?>
      </table>
      <?php if(isset($_SESSION['id_user'])){?>
      <div class='wrapModLink'><a href="#" class="modLink" id="modInfo"> modifica info</a></div>
      <?php } ?>
     </article>
    </section>
   </div>
  </div><!--colLeft1 -->
  <div id="colLeft2">
   <div id="news">
    <section>
     <h1>Contatti</h1>
     <article>
      <?php 
       echo(nl2br($arr['contatti']));
       if(isset($_SESSION['id_user'])){?>
      <div class='wrapModLink'><a href="#" class="modLink" id="modContatti">modifica contatti</a></div>
      <?php } ?>
     </article>
    </section>
   </div><!--news -->
 </div><!--colLeft2 -->
</div><!-- colLeft -->
<div id="colRight">
<div id="right-nav">
<aside>
 <section id="loginWrap">
 <?php 
  if(isset($_SESSION['id_user'])){include_once('inc/usrmenu.php'); }
  else{include_once('inc/login_form.php');} 
 ?>
 </section>

 <section id="navLink">
  <header><h1>Link</h1></header>
  <nav class="navLink">
   <?php include_once('inc/link.php'); ?>
  </nav>
 </section>

 <section id="navDownload">
  <header><h1>Materiale scaricabile</h1></header>
  <nav class="navLink">
   <?php include_once('inc/download.php'); ?>
  </nav>
 </section>
</aside>
</div><!-- right-nav -->
</div><!-- colRight -->
<div style="clear:both !important;"></div>

</div><!-- wrap -->
<div id="foot"><footer><?php require_once("inc/footer.php"); ?></footer></div>

<!-------  hidden div ------>
<div id="modDescrizDiv" class="hidden divDialogOnlyText">
  <input type="hidden" id='idPoi' value='<?php echo($id); ?>' />
  <textarea id="newDescr"><?php echo($arr['descrizione']); ?></textarea>
  <div style="text-align:center"><span id="msgDescr" class="red"></span></div>
  <input class="formSubmit" type="button" id="newDescrSave" value="salva">
  <input class="closeDialog" type="button" value="chiudi finestra">
</div>

<div id="modContattiDiv" class="hidden divDialogOnlyText">
  <input type="hidden" id='idPoi' value='<?php echo($id); ?>' />
  <textarea id="newContact"><?php echo($arr['contatti']); ?></textarea>
  <div style="text-align:center"><span id="msgContact" class="red"></span></div>
  <input class="formSubmit" type="button" id="newContactSave" value="salva">
  <input class="closeDialog" type="button" value="chiudi finestra">
</div>

<div id="modPoiDiv" class="hidden"">
  <div id="wrapForm">
  <form>
   <input type="hidden" id='idPoi' value='<?php echo($id); ?>' />
   <table>
    <tr>
     <td><label>inv/nctn</label></td>
     <td><textarea name="inv" id="inv"><?php echo($inv); ?></textarea></td>
    </tr>
    <tr>
     <td><label>nome sito</label></td>
     <td><textarea name="nome" id="nome" class="obbligatorio"><?php echo($arr['sito_nome']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>comune</label></td>
     <td>
       <input class="autocompletamento obbligatorio" id="comuneList" data-campo="comune" value="<?php echo($arr['comune']); ?>"/>   
       <input type="hidden" value="<?php echo($arr['id_comune']); ?>" id="comune" />
     </td>
    </tr>
    <tr>
     <td><label>località</label></td>
     <td>
      <select name="localita" id="localita">
       <option value="<?php echo($arr['id_localita']); ?>"><?php echo($localita); ?></option>
       <?php 
         $q1 = ("SELECT * FROM  liste.localita where id_comune = $id_comune AND id_localita != $id_localita order by localita ASC");
         $r1 = pg_query($connection, $q1);
         $righe1 = pg_num_rows($r1);
         if($righe1 > 0){
          while($row1 = pg_fetch_array($r1)){
            $id_localita=$row1['id_localita'];
            $localita=$row1['localita'];
            echo '<option value="'.$id_localita.'">'.$localita.'</option>';
          }
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>toponimo</label></td>
     <td>
      <select name="toponimo" id="toponimo">
       <option value="<?php echo($arr['id_toponimo']); ?>"><?php echo($toponimo); ?></option>
       <?php 
         $q2 = ("SELECT * FROM  liste.toponimo where id_comune = $id_comune AND id_toponimo != $id_toponimo order by toponimo ASC");
         $r2 = pg_query($connection, $q2);
         $righe2 = pg_num_rows($r2);
         if($righe2 > 0){
          while($row2 = pg_fetch_array($r2)){
            $id_toponimo=$row2['id_toponimo'];
            $toponimo=$row2['toponimo'];
            echo '<option value="'.$id_toponimo.'">'.$toponimo.'</option>';
          }
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>microtoponimo</label></td>
     <td>
      <select name="microtoponimo" id="microtoponimo">
       <option value="<?php echo($arr['id_microtoponimo']); ?>"><?php echo($microtoponimo); ?></option>
       <?php 
         $q3 = ("SELECT * FROM  liste.microtoponimo where id_toponimo = $id_toponimo AND id_microtoponimo != $id_microtoponimo order by microtoponimo ASC");
         $r3 = pg_query($connection, $q2);
         $righe3 = pg_num_rows($r2);
         if($righe3 > 0){
          while($row3 = pg_fetch_array($r2)){
            $id_microtoponimo=$row3['id_microtoponimo'];
            $microtoponimo=$row3['microtoponimo'];
            echo '<option value="'.$id_microtoponimo.'">'.$microtoponimo.'</option>';
          }
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>posizione</label></td>
     <td><textarea name="posizione" id="posizione" class="mediumDesc"><?php echo($arr['posizione']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>tipologia sito</label></td>
     <td>
      <select name="tipoSito" id="tipoSito">
       <option value="<?php echo($arr['id_tipo']); ?>"><?php echo($tipo); ?></option>
       <?php
        $q4=("SELECT * FROM liste.sito_tipo;");
        $ex4 = pg_query($connection, $q4);
        $row4 = pg_num_rows($ex4);
        for ($x = 0; $x < $row4; $x++){
           $id_tipo = pg_result($ex4, $x,"id_sito_tipo"); 	
           $tipo = pg_result($ex4, $x,"tipo");
           echo "<option value='$id_tipo'>$tipo</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>descrizione</label></td>
     <td><textarea name="descrizione" id="descrizione" class="longDesc obbligatorio"><?php echo($arr['descrizione']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>periodo</label></td>
     <td>
      <select name="periodo" id="periodo" class="obbligatorio">
       <option value="<?php echo($id_periodo); ?>"><?php echo($periodo); ?></option>
       <?php
        $q5=("SELECT * FROM liste.periodo_cultura where id_periodo_cultura != 17 AND id_periodo_cultura != $id_periodo;");
        $ex5 = pg_query($connection, $q5);
        $row5 = pg_num_rows($ex5);
        for ($x = 0; $x < $row5; $x++){
           $id_periodo = pg_result($ex5, $x,"id_periodo_cultura"); 	
           $periodo = pg_result($ex5, $x,"periodo_cultura");
           echo "<option value='$id_periodo'>$periodo</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>cronologia iniziale</label></td>
     <td><textarea name="cronoi" id="cronoi"><?php echo($arr['crono_iniziale']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>cronologia finale</label></td>
     <td><textarea name="cronof" id="cronof"><?php echo($arr['crono_finale']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>funzionario</label></td>
     <td><textarea name="funzionario" id="funzionario"><?php echo($arr['funzionario']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>accessibilità</label></td>
     <td>
      <select name="accessibilita" id="accessibilita">
       <option value="<?php echo($id_accessibilita); ?>"><?php echo($arr['accessibilita']); ?></option>
       <?php
        $q6=("SELECT * FROM liste.accessibilita where id_accessibilita != 4 AND id_accessibilita != $id_accessibilita;");
        $ex6 = pg_query($connection, $q6);
        $row6 = pg_num_rows($ex6);
        for ($x = 0; $x < $row6; $x++){
           $id_accessibilita = pg_result($ex6, $x,"id_accessibilita"); 	
           $accessibilita = pg_result($ex6, $x,"accessibilita");
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
       <option value="<?php echo($id_defGen); ?>"><?php echo($def_gen); ?></option>
       <?php
        $q7=("SELECT * FROM liste.definizione_generale WHERE id_def_generale != $id_defGen order by id_def_generale;");
        $ex7 = pg_query($connection, $q7);
        $row7 = pg_num_rows($ex7);
        for ($x = 0; $x < $row7; $x++){
           $id_def_generale = pg_result($ex7, $x,"id_def_generale"); 	
           $def_gen = pg_result($ex7, $x,"def_generale");
           $ico = pg_result($ex7, $x,"ico");
           echo "<option value='$id_def_generale' data-ico='$ico'>$def_gen</option>";
         }
       ?>
      </select>
      <input type="hidden" id="ico" value='' />
     </td>
    </tr>
    <tr>
     <td><label>definizione specifica</label></td>
     <td>
      <select name="def_spec" id="def_spec">
       <option value="<?php echo($id_defSpec); ?>"><?php echo($def_spec); ?></option>
       <?php
        $q8=("SELECT * FROM liste.definizione_specifica WHERE id_def_specifica != $id_defSpec AND id_def_generale = $id_defGen order by id_def_generale;");
        $ex8 = pg_query($connection, $q8);
        $row8 = pg_num_rows($ex8);
        for ($x = 0; $x < $row8; $x++){
           $id_def_generale = pg_result($ex8, $x,"id_def_specifica"); 	
           $def_gen = pg_result($ex8, $x,"def_specifica");
           $ico = pg_result($ex8, $x,"ico");
           echo "<option value='$id_def_generale' data-ico='$ico'>$def_gen</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>stato di conservazione</label></td>
     <td>
      <select name="conservazione" id="conservazione">
       <option value="<?php echo($id_conservazione); ?>"><?php echo($conservazione); ?></option>
       <?php
        $q9=("SELECT * FROM liste.stato_conservazione where id_stato_conservazione != $id_conservazione order by id_stato_conservazione asc;");
        $ex9 = pg_query($connection, $q9);
        $row9 = pg_num_rows($ex9);
        for ($x = 0; $x < $row9; $x++){
           $id_conservazione = pg_result($ex9, $x,"id_stato_conservazione"); 	
           $conservazione = pg_result($ex9, $x,"stato_conservazione");
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
       <option value="<?php echo($id_materiale); ?>"><?php echo($materiale); ?></option>
       <?php 
        $q10=("SELECT * FROM liste.materiale where id_materiale != $id_materiale order by materiale asc;");
        $ex10 = pg_query($connection, $q10);
        $row10 = pg_num_rows($ex10);
        for ($x = 0; $x < $row10; $x++){
           $id_materiale = pg_result($ex10, $x,"id_materiale"); 	
           $materiale = pg_result($ex10, $x,"materiale");
           echo "<option value='$id_materiale'>$materiale</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>tecnica</label></td>
     <td>
      <select name="tecnica" id="tecnica">
       <option value="<?php echo($id_tecnica); ?>"><?php echo($tecnica); ?></option>
       <?php 
        $q11=("SELECT * FROM liste.tecnica where id_tecnica != $id_tecnica order by tecnica asc;");
        $ex11= pg_query($connection, $q11);
        $row11 = pg_num_rows($ex11);
        for ($x = 0; $x < $row11; $x++){
           $id_tecnica = pg_result($ex11, $x,"id_tecnica"); 	
           $tecnica = pg_result($ex11, $x,"tecnica");
           echo "<option value='$id_tecnica'>$tecnica</option>";
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><label>contatti</label></td>
     <td><textarea name="contatti" id="contatti" class="longDesc"><?php echo($arr['contatti']); ?></textarea></td>
    </tr>
    <tr>
     <td><label>note</label></td>
     <td><textarea name="note" id="note" class="longDesc"><?php echo($arr['note']); ?></textarea></td>
    </tr>
   </table>
  </form>
  <div class="error"></div>
  <div style="position:relative; width:80%; margin:10px auto;">
   <button class="submitButton" id="modPoiSave">Salva dati</button>
   <button class="submitButton" id="annullaInserimento">Annulla inserimento</button>
  </div>
 </div>
 </div>

<div id="delPoiDiv" class="hidden" style="text-align:center;">
  <input type="hidden" id='idPoi' value='<?php echo($id); ?>' />
  <h1>Stai per eliminare un punto di interesse e tutti i dati ad esso collegato!</h1>
  <h2>I dati eliminati non potranno essere più recuperati, sei sicuro di procedere con questa operazione?</h2>
  <div><span id="msgDescr" class="msgDescr red"></span></div>
  <input class="formSubmit" type="button" id="delPoi" value="elimina">
  <input class="closeDialog" type="button" value="chiudi finestra">
</div>
<script type="text/javascript" src="js/jq-ui/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jq-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="js/fade-plugin.js"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script type="text/javascript" src="js/func.js"></script> 
<script type="text/javascript" src="js/dinSelect.js"></script> 
<script type="text/javascript" >

/************* jquery ****************/
$(document).ready(function() {

 $("input:submit").hide();
 $('input:file').change(function(){if ($(this).val()) {$('input:submit').show();}});

 $('#modDescr').click(function(){
  $("#modDescrizDiv").dialog({
   title: "Aggiorna descrizione.",
   height: 'auto',
   width: 500,
   resizable: false    
  }); // dialog;
  $('#modDescrizDiv').dialog("open");
  $('#modDescrizDiv').dialog("option", "position", ['center','center']);
  $('#newDescrSave').click(function(){
   var newDescr = $('#newDescr').val();
   var idPoi = $('#idPoi').val();
   $.ajax({
    url: 'inc/descrUpdate.php',
    type: 'POST', 
    data: {idPoi : idPoi, descrizione:newDescr }, 
    success: function(data){
     $('#msgDescr').text(data).delay(2000).fadeOut(function(){ location.reload(); });
    }
   }); //fine ajax
  });
 });

 $('#modContatti').click(function(){
  $("#modContattiDiv").dialog({
   title: "Aggiorna contatti.",
   height: 'auto',
   width: 500,
   resizable: false    
  }); // dialog;
  $('#modContattiDiv').dialog("open");
  $('#modContattiDiv').dialog("option", "position", ['center','center']);
  $('#newContactSave').click(function(){
   var newContact = $('#newContact').val();
   var idPoi = $('#idPoi').val();
   $.ajax({
    url: 'inc/contactUpdate.php',
    type: 'POST', 
    data: {idPoi : idPoi, contatti:newContact }, 
    success: function(data){
     $('#msgContact').text(data).delay(2000).fadeOut(function(){ location.reload(); });
    }
   }); //fine ajax
  });
 });

 $('#modFoto').click(function(){
  $("#divFotoDialog").dialog({
   title: "Aggiungi foto.",
   height: 'auto',
   width: 500,
   resizable: false    
  }); // dialog;
  $('#divFotoDialog').dialog("open");
  $('#divFotoDialog').dialog("option", "position", ['center','center']);
 });

 $('#modInfo').click(function(){
  $("#modPoiDiv").dialog({
   title: "Aggiorna info sito.",
   height: 'auto',
   width: 800,
   resizable: false    
  }); // dialog;
  $('#modPoiDiv').dialog("open");
  $('#modPoiDiv').dialog("option", "position", ['center','center']);
  $('#modPoiSave').click(function(){
   var idPoi = $('#idPoi').val();
   var inv = $('#inv').val();
   var sito_nome = $('#nome').val();
   var id_comune = $('#comune').val();
   var id_localita = $('#localita').val();
   var id_toponimo = $('#toponimo').val();
   var id_microtoponimo = $('#microtoponimo').val();
   var posizione = $('#posizione').val();
   var descrizione = $('textarea[name="descrizione"]').val();
   var id_periodo = $('#periodo').val();
   var crono_iniziale = $('#cronoi').val();
   var crono_finale = $('#cronof').val();
   var funzionario = $('#funzionario').val();
   var id_accessibilita = $('#accessibilita').val();
   var id_def_generale = $('#def_gen').val();
   var id_def_specifica = $('#def_spec').val();
   var id_stato_conservazione = $('#conservazione').val();
   var id_materiale = $('#materiale').val();
   var id_tecnica = $('#tecnica').val();
   var id_icone = $('#ico').val();
   var note = $('#note').val();
   var contatti = $('#contatti').val();
   var id_sito_tipo = $('#tipoSito').val();

   var errori = "Prima di proseguire correggi i seguenti errori:<br/>";
   if(!sito_nome){errori += 'Inserisci un nome per il sito<br/>'; $('#nome').addClass('errorClass');}
   else{$('#nome').removeClass('errorClass');};  
  
   if(!id_comune){errori += 'Seleziona un Comune dalla lista<br/>'; $('#comuneList').addClass('errorClass');}
   else{$('#comuneList').removeClass('errorClass');};  

   if(!id_sito_tipo){errori += 'Seleziona una tipologia per il sito<br/>'; $('#tipoSito').addClass('errorClass');}
   else{$('#tipoSito').removeClass('errorClass');};  

   if(!descrizione){errori += 'Inserisci una descrizione anche breve<br/>'; $('#descrizione').addClass('errorClass');}
   else{$('#descrizione').removeClass('errorClass');};  

   if(!id_periodo){errori += 'Seleziona un periodo<br/>'; $('#periodo').addClass('errorClass');}
   else{$('#periodo').removeClass('errorClass');};  

   if(!id_accessibilita){errori += 'Seleziona il tipo di accesso al sito<br/>'; $('#accessibilita').addClass('errorClass');}
   else{$('#accessibilita').removeClass('errorClass');};  

   if(!id_def_generale){errori += 'Seleziona una definizione generale che identifichi il sito<br/>'; $('#def_gen').addClass('errorClass');}
   else{$('#def_gen').removeClass('errorClass');};  

   if(!id_stato_conservazione){errori += 'Definisci lo stato di conservazione<br/>';$('#conservazione').addClass('errorClass');}
   else{$('#conservazione').removeClass('errorClass');}

   if(!sito_nome || !id_comune || !id_sito_tipo || !descrizione || !id_periodo || !id_accessibilita || !id_def_generale || !id_stato_conservazione){
    $('.error').html(errori); return false;
  }else{
   $.ajax({
    url: 'inc/infoUpdate.php',
    type: 'POST', 
    data: {idPoi:idPoi, inv:inv, sito_nome:sito_nome, id_comune:id_comune, id_localita:id_localita, id_toponimo:id_toponimo, id_microtoponimo:id_microtoponimo, posizione:posizione, descrizione:descrizione, id_periodo:id_periodo, crono_iniziale:crono_iniziale, crono_finale:crono_finale, funzionario:funzionario, id_accessibilita:id_accessibilita, id_def_generale:id_def_generale, id_def_specifica:id_def_specifica, id_stato_conservazione:id_stato_conservazione, id_materiale:id_materiale, id_tecnica:id_tecnica, id_icone:id_icone, note:note, contatti:contatti, id_sito_tipo:id_sito_tipo}, 
    success: function(data){
     $('.error').text(data).delay(2000).fadeOut(function(){ location.reload(); });
    }
   }); //fine ajax
  }
  });
 });

 $('#delPoiLink').click(function(){
  $("#delPoiDiv").dialog({
   title: "Elimina punto d'interesse",
   height: 'auto',
   width: 500,
   resizable: false,
   modal:true   
  }); // dialog;
  $('#delPoiDiv').dialog("open");
  $('#delPoiDiv').dialog("option", "position", ['top','center']);
  $('#delPoi').click(function(){
   var idPoi = $('#idPoi').val();
   $.ajax({
    url: 'inc/delPoi.php',
    type: 'POST', 
    data: {idPoi : idPoi}, 
    success: function(data){
     $('.msgDescr').text(data).delay(2000).fadeOut(function(){ window.location.href='poiList.php'; });
    }
   }); //fine ajax
  });
 });
});

$('#annullaInserimento').click(function(){location.reload();});

$('.thumb').each(function(){
  var imgThumb = $(this);
  var maxWidth = 200; // Max width for the image
  var minHeight = 132;    // Max height for the image
  var ratio = 0;  // Used for aspect ratio
  var ratio2 = 0;
  var width = imgThumb.width();
  var height = imgThumb.height();
  console.log(width, height);
  if (width >= height) {
   ratio = maxWidth / width;   // get ratio for scaling image
   height = height * ratio;    // Reset height to match scaled image
   if (height > minHeight) {
     ratio2 = minHeight / height;
     width = maxWidth * ratio2;    // Reset width to match scaled image
     imgThumb.css({width:width, height:minHeight});
     height = height * ratio2;
   }else {
     imgThumb.css({width:maxWidth, height:height});
     width = width * ratio;    // Reset width to match scaled image
   }
  }
  if(height > width){
    ratio = minHeight / height; 
    width = width * ratio;    // Reset width to match scaled image
    imgThumb.css({width:width, height:minHeight});
    height = height * ratio;
  }
});



/*************************************/
/************ openlayers *************/
   var map, extent, osm, arrayOSM, arrayAerial, baseOSM, baseAerial, poi; //layer
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
   maxResolution: 'auto',
   maxExtent: new OpenLayers.Bounds (1160225,5397418, 1244871,5441706),
   //maxExtent: new OpenLayers.Bounds (-180,-90, 180,90),
   //allOverlays: true,
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

extent = new OpenLayers.Bounds(1160225,5397418, 1244871,5441706);

var wgs = new OpenLayers.Projection("EPSG:4326");
var utm = new OpenLayers.Projection("EPSG:3857");

var ll= new OpenLayers.LonLat(lon,lat);
var newll =  ll.transform(wgs, map.getProjectionObject());
map.setCenter(newll,15);
 //if (!map.getCenter()) {map.zoomToExtent(extent);}
} //end init mappa
</script>
</body>
</html>
