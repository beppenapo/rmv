<?php
session_start();
include("db.php");
$email=$_POST['email'];
$password=$_POST['password'];
$url=$_POST['url'];

$result=pg_query($connection, "SELECT * FROM usr where email='$email' and attivo=1  ");
$rowCheck = pg_num_rows($result);
$array=pg_fetch_array($result);

$salt = $array['salt'];
$pwd = crypt($password, $salt);

if ($pwd == $array['pwd']) {
  $_SESSION['id_user']=$array['id'];
  $_SESSION['username']=$username;
  $_SESSION['nome']=$array["nome"];
  $_SESSION['cognome']=$array["cognome"];
  $_SESSION['classe']=$array['classe'];
  $_SESSION['email']=$array['email'];
  $_SESSION['salt']=$array['salt'];

  $log=pg_query($connection, "insert into login(id_user) values(".$array['id'].");");

  header("Location:".$url);
  exit;
} else {
  header ("Refresh: 3; URL=".$url);
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
   <meta name="generator" content="Bluefish 2.2.5" >
   <meta name="author" content="Giuseppe Naponiello" >
   <meta name="robots" content="INDEX,FOLLOW" />
   <meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
   <link href="../css/reset.css" rel="stylesheet" media="screen" />
   <link href="../css/default.css" rel="stylesheet" media="screen" />
   <link href="../css/banner.css" rel="stylesheet" media="screen" />
   <link href="../js/ol2.13/theme/default/style.css" rel="stylesheet" media="screen" />
<title>Rete Museale del Valdarno di sotto</title>
<style>
 #msg{width:50%; margin:300px auto;}
 p{margin:10px 0px;}
</style>
</head>
<body>
<div id="msg">
  <h1>Login fallito!</h1>
  <p>Riprova facendo attenzione a digitare correttamente lo username e/o la password.</p>
  <p>Se il problema sussiste è possibile che il tuo utente sia stato disabilitato.</p>
 </div>
</body>
</html>
<?php } ?>
