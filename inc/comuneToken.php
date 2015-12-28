<?
require('db.php');
$q = $_GET["term"];
$query = ("select gid, nome from comuni where nome ilike '$q%' order by nome asc");
$exec = pg_query($connection, $query);

$arr = array();

while ($row = pg_fetch_assoc($exec)) {
  $row_array['id'] = $row['gid'];
  $row_array['value'] = $row['nome'];
  array_push($arr,$row_array);
}
echo json_encode($arr);

?>
