<?php
session_start();
require("inc/db.php");
$nome=pg_escape_string($_POST['nome']);
$cognome=pg_escape_string($_POST['cognome']);
$email=pg_escape_string($_POST['email']);
$classe=$_POST['classe'];

$pwd = "";
$pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}


if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
  $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
  $password = crypt($pwd, $salt);
}

$new="insert into usr(nome, cognome, email,pwd, classe,attivo, salt) values('$nome','$cognome','$email','$password',$classe,1, '$salt') ";
$exec = pg_query($connection, $new);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{
$to      = $email;
$subject = 'Nuovo account su Montopoli';
$message = '
<html>
<head>
 <title>Nuovo account sul sito di Montopoli</title>
</head>
<body>
 <h2>Ciao '.$nome.'</h2>
 <p>Un nuovo account a tuo nome &egrave; stato registrato sul sito di Montopoli!</p>
 <p>Le tue credenziali di accesso sono:<br/>username: <b>'.$email.'</b><br/>password: <b>'.$pwd.'</b></p>
 <p>La password pu&ograve; essere modificata in qualunque momento.</p>
 <p>Vai alla pagina di login per modificare il tuo profilo: <a href="http://37.187.200.160/">http://37.187.200.160/</a></p>
</body>
</html>
';
$message = wordwrap($message, 70, "\n");

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf8\r\n";
$headers .= "From: beppenapo@arc-team.com\r\n";
$headers .= "Organization: Sistema Museale Valdarno di Sotto\r\n";
$headers .= "Reply-To: beppenapo@arc-team.com\r\n";
$headers .= "Return-Path: beppenapo@arc-team.com\r\n";
$headers .= "Content-Transfer-Encoding: binary";
$headers .= "X-Priority: 3\r\n";
$headers .= "X-Mailer: PHP". phpversion() ."\r\n";

if(mail($to, $subject, $message, $headers, '-f beppenapo@arc-team.com')){
 $mailMsg = "Una mail è stata inviata all'indirizzo mail indicato nel form.<br/>Se la mail non è presente nella casella di posta utilizzata, è probabile che sia stata spostata nella casella di spam.";
}else{
 $mailMsg = "Si è verificato un errore nell'invio della mail";
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
  <h1>Il nuovo utente è stato creato<br/><?php echo $mailMsg; ?></h1>
  <a href="utenti.php" title="torna alla pagina degli utenti.">torna alla pagina utenti</a>
 </div>
</body>
</html>
<?php } ?>

