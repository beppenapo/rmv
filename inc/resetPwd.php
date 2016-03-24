<?php
require_once("db.php");
$id=$_POST['idusr'];
$email=$_POST['mail'];

$pwd = "";
$pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}


if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
  $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
  $password = crypt($pwd, $salt);
}

$new="update usr set pwd = '$password', salt = '$salt' where id = $id ";
$exec = pg_query($connection, $new);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{

$to      = $email;
$subject = 'Aggiornamento dati Montopoli';
$message = '
<html>
<head>
 <title>Aggiornamento dati Montopoli</title>
</head>
<body>
 <h2>Ciao '.$nome.'</h2>
 <p>Le tue nuove credenziali di accesso sono:<br/>username: <b>'.$email.'</b><br/>password: <b>'.$pwd.'</b></p>
 <p>La password pu&ograve; essere modificata in qualunque momento dal tuo profilo.</p>
 <p>Vai alla pagina di login per modificare il tuo profilo: <a href="http://37.187.200.160/">http://37.187.200.160/</a></p>
</body>
</html>
';
$message = wordwrap($message, 70, "\n");

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: beppenapo@arc-team.com\r\n";
$headers .= "Organization: Sistema Museale Valdarno di Sotto\r\n";
$headers .= "Reply-To: beppenapo@arc-team.com\r\n";
$headers .= "Return-Path: beppenapo@arc-team.com\r\n";
$headers .= "Content-Transfer-Encoding: binary";
$headers .= "X-Priority: 3\r\n";
$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
mail($to, $subject, $message, $headers, '-f beppenapo@arc-team.com');

echo "La password è stata resettata. L'utente riceverà una mail con la nuova password.";

}
?>

