<?php
include('db.php');
if($_POST['id']){
 $id=$_POST['id'];
 $query = ("SELECT * FROM  liste.localita where id_comune = $id order by localita ASC");
 $result = pg_query($connection, $query);
 $righe = pg_num_rows($result);
 if($righe > 0){
  echo '<option value="15">-- seleziona località --</option>';
  while($row = pg_fetch_array($result)){
   $id_localita=$row['id_localita'];
   $localita=$row['localita'];
   echo '<option value="'.$id_localita.'">'.$localita.'</option>';
  }
  //echo '<option value="15">Non determinabile</option>';
 }else{
  echo '<option value="15">-- nessuna località associata --</option>';
  //echo '<option value="15">Non determinabile</option>';
 }
}
?>
