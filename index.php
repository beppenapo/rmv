<?php
session_start();
require("inc/db.php");
?>
<!DOCTYPE html>
<html lang="it">
<head>
<?php require("inc/meta.php"); ?>
<link href="js/flexslider/flexslider.css" rel="stylesheet" media="screen" />
<style>
    .intro a{color:#424242;font-weight:bold;}
    .intro a:hover{color:#fff;}
    .flex-control-nav {bottom:10px !important;}
</style>
</head>
<body>
 <header id="head"><?php require_once('inc/head.php')?></header>
<div id="wrapMain">
 <?php require_once("inc/slider_home.php"); ?>
 <div class="intro">
  <div class="wrapContent">
   <div class="colDx">
    <p>Visitare il Valdarno di Sotto significa immergersi in una realtà ricca di storia, di arte, di archeologia e di importanti tradizioni, muovendosi tra gli splendidi paesaggi naturali che le fanno da quadro.</p>
    <p>Il <strong>Sistema Museale del Valdarno di Sotto</strong> dal 2007 coordina i musei civici di Castelfranco di Sotto, Fucecchio, Montopoli in Valdarno, S. Miniato e S. Maria a Monte e della Diocesi di San Miniato, e ne integra le attività per rendere più efficace e incisiva la valorizzazione del patrimonio archeologico, storico-artistico e naturalistico del territorio.</p>
    <p>Per tali motivi il Sistema Museale del Valdarno di Sotto ha costruito uno strumento di prima <strong>informazione sui beni culturali del proprio territorio</strong> consultabile attraverso il web, che ne consenta una migliore conoscenza e una più efficace fruizione da parte del più ampio numero di cittadini e visitatori.</p>
    <p>&Egrave; così possibile organizzare un percorso di visita personalizzato in questa porzione del Valdarno a partire dai <a href="mappa.php" title="Accedi alla mappa" lang="it">percorsi turistico-culturali</a> con accesso tematico o effettuando una ricerca tra i <a href="poiList.php" title="Accedi alla lista completa dei punti di interesse" lang="it">punti di interesse</a>.</p>
   </div>
  </div>
 </div>

 <div id="mainContent" class="wrapContent">
  <div id="mainContentWrap">
   <section>
    <header><span lang="it">Alla scoperta del Valdarno</span></header>
    <article id="mappa">
     <i class="fa fa-globe fa-5x logo"></i>
     <p>Naviga all'interno della mappa per scoprire i punti di interesse storico o archeologico presenti sul territorio, o cerca notizie e informazioni sui musei del Valdarno.</p>
    </article>
    <a href="mappa.php" title="accedi alla mappa" class="linkSez linkSezMappa" lang="it">accedi <i class=" fa fa-arrow-right"></i></a>
   </section>
   <section>
    <header><span lang="it">I punti di interesse</span></header>
    <article id="database">
     <i class="fa fa-database fa-4x logo"></i>
     <p>Scorri l'elenco dei punti di interesse, accedi alla scheda monografica del punto e stampa le informazioni che più ti interessano.</p>
    </article>
    <a href="poiList.php" title="accedi alla lista dei punti di interesse" class="linkSez linkSezPoi" lang="it">accedi <i class=" fa fa-arrow-right"></i></a>
   </section>
   <section>
    <header><span lang="it">Il progetto</span></header>
    <article id="info">
     <i class="fa fa-users fa-4x logo"></i>
     <p>Il sito è il frutto del lavoro di tante persone che hanno deciso di unirsi per dare forma ad un'idea. Per sapere chi sono queste persone e come è nata tale idea, visita la sezione dedicate alle informazioni sul progetto</p>
    </article>
    <a href="http://www.valdarnomusei.it/htm.htm" target="_blank" title="[link esterno] scopri di più sul progetto" class="linkSez linkSezInfo" lang="it">accedi <i class=" fa fa-arrow-right"></i></a>
   </section>
  </div>
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
 </div>


 <div style="clear:both !important;"></div>
</div> <!-- wrapMain -->
 <footer><?php require_once("inc/footer.php"); ?></footer>


<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/lang/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="js/lang/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script src="js/flexslider/jquery.flexslider.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript" src="js/func.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 $("#homeLink").addClass('active').click(function(e){e.preventDefault();});

 $('.flexslider').flexslider({
  animation: 'slide',
  easing: 'easeInQuad',
  controlNav: true,
  directionNav: true,
  pauseOnAction: false,
  pauseOnHover: true,
  slideshowSpeed: 10000,
  animationSpeed: 700,
  before: function() {$('.overlayDiv').hide();},
  after: function() {$('.overlayDiv').fadeIn(2000);}
 });
});
</script>
</body>
</html>
