<?php
require_once("db.php");
$id=18;
$email="lemmifrancesca@gmail.com";
$pwd = "fl3mma6laMej0";

$pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
//for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}
if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
    $password = crypt($pwd, $salt);
}
;

$new="update usr set pwd = '$password', salt = '$salt' where id = $id ";
$exec = pg_query($connection, $new);
if(!$exec){die("Errore nella query: \n" . pg_last_error($connection));}
else{echo "ok ".$pwd." / ".$password." / ".$salt;};

?>
