<?php
require('db.php');

$q = $_GET["term"];
$query = ("
  SELECT id, gid, nome
  FROM comuni 
  WHERE nome ilike '%" . $q . "%'
  ORDER BY nome ASC
"); 

$exec = pg_query($connection, $query);

$return_arr = array();  
while ($row = pg_fetch_assoc($exec)) {
  $row_array['id'] = $row['id'];
  $row_array['value'] = $row['nome'];
  $row_array['gid'] = $row['gid'];
  array_push($return_arr,$row_array);
}
echo json_encode($return_arr);

?>
