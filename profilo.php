<?php
session_start();
require_once("inc/db.php");
$classe = ($_SESSION['classe'] == 1) 
  ? '<select name="classe" id="classe" required>
      <option value="1">Amministratore</option>
      <option value="2">Utente semplice</option>
     </select>'
  :'<input type="text" value="Utente semplice" disabled>
    <input type="hidden" value="2">';
?>
<!DOCTYPE html>
<html>
<head>
    <?php require("inc/meta.php"); ?>
    <style>
        #sectMain{display:inline-block;width:80%;margin:0px auto;}
        #sectMain article{margin:30px 0px;}
        #sectMain article header{width: 50%;margin: 0px auto 15px;font-size: 1.3em;border-bottom: 1px dotted #000;}
        #sectMain article select{margin: 0;background: #fff;outline: none;display: inline-block;cursor: pointer;-webkit-appearance: none; -moz-appearance: none;text-indent: 0.01px;text-overflow: "";}
        #sectMain article input[type="submit"]{padding: 5px;background: #c25a5a;border: 1px solid #a42929;color: #fff;font-weight: bold;}
        #sectMain article input[type="submit"]:hover{background:#a42929;}
        #sectMain table{width:50%;margin:0px auto;}
        #sectMain table td{padding:5px 0px;}
    </style>
</head>
<body>
    <header id="head"><?php require_once('inc/head.php')?></header>
    <section id="sectMain">
        <article id="descrizione">
            <h1 style='text-align: center;font-size: 1.3em; line-height: 1.5em;'>Da questa pagina potrai modificare i tuoi dati personali</h1>
            <h2 class="red" style="margin:15px 0px;text-align:center;">Attenzione, tutti i campi modificabili sono obbligatori</h2>
        </article>
        <article id="formUtente">
            <header>Dati personali</header>
            <form name="utente" action="inc/modprofilo.php" method="post" accept-charset="utf-8">
                <table>
                    <tbody>
                        <tr><td><label>Nome:</label></td><td><input type="text" name="nome" id="nome" value="<?php echo($_SESSION['nome']); ?>" required/></td></tr>
                        <tr><td><label>Cognome:</label></td><td><input type="text" name="cognome" id="cognome" value="<?php echo($_SESSION['cognome']); ?>" required/></td></tr>
                        <tr><td><label>E-mail:</label></td><td><input type="email" value="<?php echo($_SESSION['email']); ?>" disabled/></td></tr>
                        <tr><td><label>Classe utente:</label></td><td><?php echo($classe);?></td></tr>
                        <tr><td colspan="2"><span id="msgdati" class="red"></span></td></tr>
                        <tr><td colspan="2"><input class="formSubmit" type="submit" value="modifica dati" /></td></tr>
                    </tbody>
                </table>
            </form>
        </article>
        <article id="upPwd" class="">
            <header>Password</header>
            <form name="pwd" action="inc/modpwd.php" method="post" accept-charset="utf-8">
                <table>
                    <tbody>
                        <tr>
                            <td><label>Nuova Password:</label></td>
                            <td><input type="password" id="newpwd" name="newpwd" placeholder="inserisci la nuova password" required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')" oninput="setCustomValidity('')" ></td>
                        </tr>
                        <tr>
                            <td><label>Conferma Password:</label></td>
                            <td><input type="password" id="checkpwd" name="checkpwd" placeholder="ridigita la password inserita" required oninvalid="this.setCustomValidity('Il campo non può essere vuoto')" oninput="setCustomValidity('')" ></td>
                        </tr>
                        <tr>
                            <td colspan="2"><span id="msg2" class="red"></span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input class="formSubmit" type="submit" value="modifica password" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </article>
    </section>
<div id="nav">
<aside>
    <section id="loginWrap">
     <?php 
      if(isset($_SESSION['id_user'])){include_once('inc/usrmenu.php'); }
      else{include_once('inc/login_form.php');} 
     ?>
    </section>
    <section id="navLink">
     <header><h1><i class="fa fa-link"></i> Link</h1></header>
     <nav class="navLink"><?php include_once('inc/link.php'); ?></nav>
    </section>
</aside>
</div>
<div style="clear:both !important;"></div>

</div><!-- wrap -->
<footer><?php require_once("inc/footer.php"); ?></footer>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/fade-plugin.js"></script>
<script type="text/javascript" src="js/ol2.13/OpenLayers.js"></script> 
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/func.js"></script> 
<script type="text/javascript">
window.onload = function () {
    document.getElementById("newpwd").onchange = validatePassword;
    document.getElementById("checkpwd").onchange = validatePassword;
}

function validatePassword(){
 var pass2=document.getElementById("checkpwd").value;
 var pass1=document.getElementById("newpwd").value;
 if(pass1!=pass2) document.getElementById("checkpwd").setCustomValidity("Attenzione le password non coincidono!");
 else document.getElementById("checkpwd").setCustomValidity('');  
}

</script>
</body>
</html>
