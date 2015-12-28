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
