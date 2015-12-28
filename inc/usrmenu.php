<?php $pageURL = basename($_SERVER['PHP_SELF']); ?>

<div>
<header><h1><?php echo 'Ciao '.$_SESSION['nome']; ?></h1></header>
<nav class="navLink">
 <ul>
  <li><a href="inc/logout.php">logout</a></li>
  <li><a href="profilo.php">Modifica profilo</a></li>
  <?php if($_SESSION['classe']==1){?>
  <li><a href="utenti.php">Modifica utenti</a></li>
  <?php } ?>
 </ul>
</nav>
</div>
<div>
<header><h1><i class="fa fa-map-marker"></i> Gestione punti</h1></header>
<nav class="navLink">
 <ul>
  <li><a href="newpoi.php">Nuovo punto interesse</a></li>
  <?php if($pageURL=='poi.php'){echo '<li><a href="#" id="delPoiLink">Elimina punto di interesse</a></li>';} ?>
 </ul>
</nav>
</div>
<div>
<header><h1><i class="fa fa-th-list"></i> Gestione liste</h1></header>
<nav class="navLink">
 <ul>
  <li><a href="localitaList.php">località</a></li>
  <li><a href="#">toponimi</a></li>
  <li><a href="#">microtoponimi</a></li>
 </ul>
</nav>
</div>
