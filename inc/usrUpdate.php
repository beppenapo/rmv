<?php
session_start();
require_once("db.php");
$id= $_POST['idUsr'];
$query=("SELECT * FROM usr where id = $id;");
$result=pg_query($connection, $query);
$row = pg_num_rows($result);
$a = pg_fetch_array($result);
$classe = ($a['classe'] == 1) 
  ? '<select name="classe" id="usrclasse" required>
      <option value="1">Amministratore</option>
      <option value="2">Utente semplice</option>
     </select>'
  :'<select name="classe" id="usrclasse" required>
      <option value="2">Utente semplice</option>
      <option value="1">Amministratore</option>
     </select>';

$stato = ($a['attivo'] == 1) 
  ? '<select name="stato" id="usrstato" required>
      <option value="1">Attivo</option>
      <option value="2">Non attivo</option>
     </select>'
  :'<select name="stato" id="usrstato" required>
      <option value="2">Non attivo</option>
      <option value="1">Attivo</option>
     </select>';
?>

  <form name="usr" action="" method="post" accept-charset="utf-8">
    <input type="hidden" name="idusr" value="<?php echo($id);?>">
    <input type="hidden" name="mail" id="mail" value="<?php echo($a['email']); ?>" >
    <h2 class="red">Attenzione, tutti i campi modificabili sono obbligatori</h2>
    <table>
     <tbody>
      <tr>
       <td style="width:25%;"><label>Nome:</label></td><td><input type="text" name="nome" id="usrnome" value="<?php echo($a['nome']); ?>" required/></td>
      </tr>
      <tr>
       <td><label>Cognome:</label></td><td><input type="text" name="cognome" id="usrcognome" value="<?php echo($a['cognome']); ?>" required/></td>
      </tr>
      <tr>
       <td><label>E-mail:</label></td><td><input type="email" value="<?php echo($a['email']); ?>" disabled/></td>
      </tr>
      <tr>
       <td><label>Classe utente:</label></td><td><?php echo($classe);?></td>
      </tr>
      <tr>
       <td><label>Stato utente:</label></td><td><?php echo($stato);?></td>
      </tr>
      <tr>
       <td><label>Password:</label></td><td><a href="#" id="resetPwd">reset password</a></td>
      </tr>
      <tr>
       <td colspan="2"><span id="msg" class="red"></span></td>
      </tr>
      <tr>
       <td colspan="2"><input class="formSubmit" type="button" value="salva" /></td>
      </tr>
     </tbody>
    </table>
  </form>
