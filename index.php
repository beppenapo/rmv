<?php
session_start();
require("inc/db.php");
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8" />
<meta name="generator" content="gedit" >
<meta name="author" content="Giuseppe Naponiello" >
<meta name="robots" content="INDEX,FOLLOW" />
<meta name="copyright" content="&copy;2014 Rete Museale del Valdarno" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="css/responsive.css" rel="stylesheet" media="screen" />
<link href="js/flexslider/flexslider.css" rel="stylesheet" media="screen" />

<title>Rete Museale del Valdarno di sotto</title>
 
</head>
<body>
 <header id="head"><?php require_once('inc/head.php')?></header>
<div id="wrapMain">
 <?php require_once("inc/slider_home.php"); ?>
 <div class="intro">
  <div class="wrapContent">
   <div class="colSx">
    <img src="img/torre_montopoli.png" />
   </div>
   <div class="colDx">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque id lectus id augue cursus porttitor vitae in neque. Mauris at imperdiet augue, elementum scelerisque purus. Sed facilisis et sapien vel consectetur. Nam dictum eu lectus vitae rhoncus. Sed in dignissim augue, sit amet laoreet lacus. Fusce placerat, lorem vel suscipit congue, elit ex egestas nulla, in efficitur orci urna eget lorem. Fusce ac lobortis mauris. Integer quis augue mi. Fusce risus elit, congue vel elementum at, porta nec augue. Donec at magna finibus, finibus lectus sed, sodales sapien.</p>
   </div>
  </div>
 </div> 

 <div id="mainContent" class="wrapContent">
  <div id="mainContentWrap">
   <section>
    <header><span lang="it">La mappa</span></header>
    <article id="mappa">
     <i class="fa fa-globe fa-5x logo"></i>
     <p>Naviga all'interno della mappa per scoprire i punti di interesse storico o archeologico presenti sul territorio, o cerca notizie e informazioni sui musei del Valdarno.</p>    
    </article>
    <a href="mappa.php" title="accedi alla mappa" class="linkSez linkSezMappa">accedi <i class=" fa fa-arrow-right"></i></a>
   </section>
   <section>
    <header><span lang="it">Il database</span></header>
    <article id="database">
     <i class="fa fa-database fa-4x logo"></i>
     <p>Scorri l'elenco dei punti di interesse, accedi alla scheda monografica del punto e stampa le informazioni che più ti interessano.</p>
    </article>
    <a href="poiList.php" title="accedi alla lista dei punti di interesse" class="linkSez linkSezPoi">accedi <i class=" fa fa-arrow-right"></i></a>
   </section>
   <section> 
    <header><span lang="it">Il progetto</span></header>
    <article id="info">
     <i class="fa fa-users fa-4x logo"></i>
     <p>Il sito è il frutto del lavoro di tante persone che hanno deciso di unirsi per dare forma ad un'idea. Per sapere chi sono queste persone e come è nata tale idea, visita la sezione dedicate alle informazioni sul progetto</p>
    </article>
    <a href="info.php" title="scopri di più sul progetto" class="linkSez linkSezInfo">accedi <i class=" fa fa-arrow-right"></i></a>
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
 <footer><?php require_once("inc/footer_test.php"); ?></footer>


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
  controlNav: false, 
  directionNav: false, 
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
