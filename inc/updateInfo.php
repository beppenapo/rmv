<?php
session_start();
require("db.php");
$d = new DateTime(null, new DateTimeZone('Europe/Rome'));
$data = $d->format('Y-m-d H:i:s');
$q ="
update sito set 
      inv = '".pg_escape_string($_POST['inv'])."'
    , sito_nome = '".pg_escape_string($_POST['nome'])."'
    , posizione = '".pg_escape_string($_POST['posizione'])."'
    , crono_iniziale = '".pg_escape_string($_POST['cronoiniz'])."'
    , crono_finale = '".pg_escape_string($_POST['cronofin'])."'
    , note = '".pg_escape_string($_POST['note'])."'
    , funzionario = '".pg_escape_string($_POST['funzionario'])."'
    , id_accessibilita = ".$_POST['accessibilita']."
    , id_def_generale = ".$_POST['defgen']."
    , id_def_specifica = ".$_POST['defspec']."
    , id_materiale = ".$_POST['materiale']."
    , id_microtoponimo = ".$_POST['microtoponimo']."
    , id_sito_tipo = ".$_POST['tipologia']."
    , id_stato_conservazione = ".$_POST['conservazione']."
    , id_tecnica = ".$_POST['tecnica']."
    , id_toponimo = ".$_POST['toponimo']."
    , id_localita = ".$_POST['localita']."
    , id_icone = ".$_POST['icona']."
    , id_periodo = ".$_POST['periodo']."
    , id_comune = ".$_POST['comune']."
    , contatti = '".pg_escape_string($_POST['contatti'])."'
    , link = '".pg_escape_string($_POST['link'])."'
    , data_modifica = '".$data."'
    , utente_modifica = ".$_SESSION['id_user']."
where id=".$_POST['id'];
$e = pg_query($connection, $q);
if(!$e){die("Errore nella query: \n" . pg_last_error($connection));}else{echo "Le informazioni sono state correttamente aggiornate"; }
?>
