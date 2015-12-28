<?php
session_start();
require("inc/db.php");
//require 'mail/PHPMailerAutoload.php';
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
 <p>La password pu&ograve; essere modificata in qualunque momento dal tuo profilo.</p>
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
mail($to, $subject, $message, $headers, '-f beppenapo@arc-team.com');

echo "<h1>Utente registrato</h1>";

}
?>

