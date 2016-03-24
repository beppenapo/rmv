<?php
session_start();
require_once("db.php");
$id=$_POST['idPoi'];
$descrizione=pg_escape_string($_POST['descrizione']);

$inv=pg_escape_string($_POST['inv']);
$sito_nome=pg_escape_string($_POST['sito_nome']);
$id_comune=$_POST['id_comune'];
$id_localita=$_POST['id_localita'];
$id_toponimo=$_POST['id_toponimo'];
$id_microtoponimo=$_POST['id_microtoponimo'];
$posizione=pg_escape_string($_POST['posizione']);
$descrizione=pg_escape_string($_POST['descrizione']);
$id_periodo=$_POST['id_periodo'];
$crono_iniziale=pg_escape_string($_POST['crono_iniziale']);
$crono_finale=pg_escape_string($_POST['crono_finale']);
$funzionario=pg_escape_string($_POST['funzionario']);
$id_accessibilita=$_POST['id_accessibilita'];
$id_def_generale=$_POST['id_def_generale'];
$id_def_specifica=$_POST['id_def_specifica'];
$id_stato_conservazione=$_POST['id_stato_conservazione'];
$id_materiale=$_POST['id_materiale'];
$id_tecnica=$_POST['id_tecnica'];
$id_icone=$_POST['id_icone'];
$note=pg_escape_string($_POST['note']);
$contatti=pg_escape_string($_POST['contatti']);
$id_sito_tipo=$_POST['id_sito_tipo'];



$update="
UPDATE sito 
SET inv='$inv', posizione='$posizione', descrizione='$descrizione', crono_iniziale='$crono_iniziale', crono_finale='$crono_finale',  note='$note', funzionario='$funzionario', id_accessibilita=$id_accessibilita, id_def_generale=$id_def_generale, id_def_specifica=$id_def_specifica, id_materiale=$id_materiale, id_microtoponimo=$id_microtoponimo, id_sito_tipo=$id_sito_tipo, id_stato_conservazione=$id_stato_conservazione, id_tecnica=$id_tecnica, id_toponimo=$id_toponimo, sito_nome='$sito_nome', id_localita=$id_localita, id_icone=$id_icone, id_periodo=$id_periodo, id_comune=$id_comune, contatti='$contatti' where id=$id";
$exec = pg_query($connection, $update);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{echo "Le informazioni sono state modificate"; }
?>
