<?php
session_start();
require("inc/db.php");
require 'inc/mail/PHPMailerAutoload.php';
$nome=pg_escape_string($_POST['nome']);
$cognome=pg_escape_string($_POST['cognome']);
$email=pg_escape_string($_POST['email']);
$classe=$_POST['classe'];
$name = $nome.' '.$cognome;
$pwd = "";
$pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}


if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
  $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
  $password = crypt($pwd, $salt);
}

$a = "insert into usr(nome, cognome, email,pwd, classe,attivo, salt) values('$nome','$cognome','$email','$password',$classe,1, '$salt'); ";
$b = pg_query($connection, $a);
if($b){
    $mail = new PHPMailer;
    $body = file_get_contents('/var/www/mail.html');
    $body = str_replace('%username%', $email, $body);
    $body = str_replace('%password%', $pwd, $body);
    $body = str_replace('%utente%', $name, $body);
    $mail->Host = "smtp.arc-team.com";
    $mail->Mailer = "smtp";
    $mail->Port = 25;
    $mail->SMTPSecure = 'tsl';
    $mail->SMTPAuth = true;
    $mail->Username   = "info@arc-team.com";
    $mail->Password   = "Arc-T3amV3";
    $mail->setFrom('info@arc-team.com', 'Arc-Team');
    $mail->addReplyTo('info@arc-team.com', 'Arc-Team');
    $mail->AddAddress($email, $name);
    $mail->Subject = 'Nuovo account sul sito Valdarno Musei';
    $mail->isHTML(true); 
    $mail->msgHTML($body, dirname(__FILE__));
    $mail->AltBody = 'Ciao '.$nome.' '.$cognome.'\nUn nuovo account è stato creato a tuo nome sul sito di Valdarno Musei\nLe tue credenziali per accedere al sistema sono le seguenti:\nutente '.$email.'\npassword '.$pwd.'\nVai su http://37.187.200.160/index.php per efettuare il login e inizia re le tue sessioni di lavoro.\nUn saluto dal gruppo di lavoro.';
    //$mail->SMTPDebug  = 1;
    if($mail->Send()) {$msg = "Una mail &egrave; stata inviata all&#39;indirizzo mail indicato nel form.<br/>Se la mail non &egrave; presente nella casella di posta utilizzata, &egrave; probabile che sia stata spostata nella casella di spam.";} else { $msg =  "Errore nell'invio della mail': " . $mail->ErrorInfo;}
}else{
    $msg = "Errore nella creazione dell&#39;utente, riprova.<br/>Se l&#39;errore persiste contatta il responsabile web al seguente indirizzo di posta elettronica: beppenapo@gmail.com";
}

   

?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8" />
 <meta name="generator" content="gedit" >
 <meta name="author" content="Giuseppe Naponiello" >
 <meta name="robots" content="INDEX,FOLLOW" />
 <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />

 <link href="css/reset.css" rel="stylesheet" media="screen" />
 <link href="css/default.css" rel="stylesheet" media="screen" />
 
 <title>Rete Museale del Valdarno di sotto</title>
 
 <style>
  #divMsg{position:absolute;top:5%;left:20%;width:40%;height:30%;background-color:#fff;border-radius:6px;padding:10%;text-align:center;}
  #divMsg h1{margin-bottom:10px;}
 </style>
</head>
<body>
 <div id="divMsg">
  <h1><?php echo $msg; ?></h1>
  <a href="utenti.php" title="torna alla pagina degli utenti.">torna alla pagina utenti</a>
 </div>
</body>
</html>

