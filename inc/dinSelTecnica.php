<?php
include('db.php');
if($_POST['id']){
 $id=$_POST['id'];
 $query = ("SELECT * FROM  liste.tecnica where id_materiale = $id order by tecnica ASC");
 $result = pg_query($connection, $query);
 $righe = pg_num_rows($result);
 if($righe > 0){
  echo '<option value="27">-- seleziona tecnica --</option>';
  while($row = pg_fetch_array($result)){
   $id_tecnica=$row['id_tecnica'];
   $tecnica=$row['tecnica'];
   echo '<option value="'.$id_tecnica.'">'.$tecnica.'</option>';
  }
  echo '<option value="27">Non determinabile</option>';
 }else{
  echo '<option value="27">-- seleziona tecnica --</option>';
  echo '<option value="27">Non determinabile</option>';
 }
}
?>
