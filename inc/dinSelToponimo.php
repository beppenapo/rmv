<?php
include('db.php');
if($_POST['id']){
 $id=$_POST['id'];
 $query = ("SELECT id_toponimo, toponimo FROM  liste.toponimo where id_localita = $id order by toponimo ASC");
 $result = pg_query($connection, $query);
 $righe = pg_num_rows($result);
 if($righe > 0){
  echo '<option value="0" selected disabled>-- seleziona toponimo --</option>';
  while($row = pg_fetch_array($result)){
   $id_toponimo=$row['id_toponimo'];
   $toponimo=$row['toponimo'];
   echo '<option value="'.$id_toponimo.'">'.$toponimo.'</option>';
  }
 }else{
  echo '<option value="12" selected>-- nessun toponimo associato --</option>';
 }
}
?>
