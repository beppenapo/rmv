<?php
require('db.php');
$q=$_POST['q'];
$query=("
SELECT distinct sito.id, comuni.id as id_comune, comuni.nome AS comune, sito.sito_nome AS poi, sito_tipo.tipo, definizione_specifica.def_specifica, periodo_cultura.periodo_cultura AS periodo, accessibilita.accessibilita, sito.data_compilazione
FROM sito, liste.localita, comuni, liste.sito_tipo, liste.definizione_specifica, liste.periodo_cultura, liste.accessibilita
WHERE sito.id_comune = comuni.gid AND sito.id_sito_tipo = sito_tipo.id_sito_tipo AND sito.id_def_specifica = definizione_specifica.id_def_specifica AND sito.id_periodo = periodo_cultura.id_periodo_cultura AND sito.id_accessibilita = accessibilita.id_accessibilita and $q order by comune asc, data_compilazione desc, poi asc;");
$result=pg_query($connection, $query);
$row = pg_num_rows($result);

if($row > 0) {
    for ($x = 0; $x < $row; $x++){
      $id = pg_result($result, $x,"id");
      $id_comune = pg_result($result, $x,"id_comune");
      $comune = pg_result($result, $x,"comune");
      $poi = pg_result($result, $x,"poi");
      $tipo = pg_result($result, $x,"def_specifica");
      $periodo = pg_result($result, $x,"periodo");
      $accessibilita = pg_result($result, $x,"accessibilita");

      $accessibilita = ($accessibilita == 'non determinabile')?'':$accessibilita;
      $tipo = ($tipo == 'non determinabile')?'':$tipo;      
      $periodo = ($periodo == 'Non determinabile')?'':$periodo;

      echo "<tr data-poi='".$id."' data-comune='".$id_comune."'class='poiLink'><td>$comune</td><td>$poi</td><td>$tipo</td><td>$periodo</td><td>$accessibilita</td></tr>";

    }
   }
?>
