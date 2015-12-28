<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>
<header><h1><i class="fa fa-sign-in"></i> Login</h1></header>
<form name="login" action="inc/login_script.php" method="post" accept-charset="utf-8">
 <ul>
  <li>
   <i class="fa fa-envelope fa-fw"></i>
   <input type="email" name="email" lang="it" placeholder="email" id="usr" required>
  </li>
  <li>
   <i class="fa fa-lock  fa-fw"></i>
   <input type="password" name="password" lang="it" placeholder="password" id="pwd" required>
   <input type='hidden' name='url' value="<?php echo curPageURL(); ?>" />
  </li>
  <li><input type="submit" value="entra" id="loginButton"></li>
 </ul>
</form>
