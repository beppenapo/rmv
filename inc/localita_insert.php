<?php
session_start();
require_once("db.php");
$localita=pg_escape_string($_POST['localita']);
$comune=$_POST['comuneGid'];

$insert="insert into liste.localita(id_comune, localita)values($comune, '$localita')";
$exec = pg_query($connection, $insert);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{
 header ("Refresh: 3; URL=../localitaList.php");
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

</head>
<body>

<div id="wrap">
 <div id="colLeft">
  <h1>La località <strong><?php echo $localita ?></strong> è stata correttamente inserita.</h1>
 </div>
</div><!-- colLeft -->
<div style="clear:both !important;"></div>
</div><!-- wrap -->
</body>
</html>
<?php } ?>

