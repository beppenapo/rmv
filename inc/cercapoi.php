<?php
require("db.php");
$q = $_GET["term"];
$query=("
SELECT s.id
     , s.sito_nome as nome
     , i.nome_icone as ico
     , st_xmin(s.the_geom) as xmin
     , st_xmax(s.the_geom) as xmax
     , st_ymin(s.the_geom) as ymin
     , st_ymax(s.the_geom) as ymax
     , st_X(s.the_geom) as lon
     ,st_Y(s.the_geom) as lat
 FROM sito s, liste.icone i
 where s.id_icone = i.id_icone and s.sito_nome ilike '%" . $q . "%'
 ORDER BY sito asc;
 ");
 
$exec = pg_query($connection, $query);

$return_arr = array();  
while ($row = pg_fetch_assoc($exec)) {
  $row_array['id']    = $row['id'];
  $row_array['value'] = $row['nome'];
  $row_array['ico']   = $row['ico'];
  $row_array['xmin']  = $row['xmax'];
  $row_array['ymin']  = $row['ymin'];
  $row_array['ymax']  = $row['ymax'];
  $row_array['lon']   = $row['lon'];
  $row_array['lat']   = $row['lat'];
  array_push($return_arr,$row_array);
}
echo json_encode($return_arr);
?>
