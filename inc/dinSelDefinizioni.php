<?php
include('db.php');
if($_POST['id']){
 $id=$_POST['id'];
 $query = ("SELECT * FROM  liste.definizione_specifica where id_def_generale = $id order by def_specifica ASC");
 $result = pg_query($connection, $query);
 $righe = pg_num_rows($result);
 if($righe > 0){
  while($row = pg_fetch_array($result)){
   $id_def_specifica=$row['id_def_specifica'];
   $def_specifica=$row['def_specifica'];
   echo '<option value="'.$id_def_specifica.'">'.$def_specifica.'</option>';
  }
 }
}
?>
