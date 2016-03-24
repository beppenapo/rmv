<?php
include('db.php');
if($_POST['id']){
 $id=$_POST['id'];
 $query = ("SELECT * FROM  liste.microtoponimo where id_toponimo = $id order by microtoponimo ASC");
 $result = pg_query($connection, $query);
 $righe = pg_num_rows($result);
 if($righe > 0){
  echo '<option value="1">-- seleziona microtoponimo --</option>';
  while($row = pg_fetch_array($result)){
   $id_micro=$row['id_microtoponimo'];
   $micro=$row['microtoponimo'];
   echo '<option value="'.$id_micro.'">'.$micro.'</option>';
  }
  //echo '<option value="1">Non determinabile</option>';
 }else{
  echo '<option value="1">-- nessun microtoponimo associato--</option>';
  //echo '<option value="1">Non determinabile</option>';
 }
}
?>
