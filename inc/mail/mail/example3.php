<?php

// include SMTP Email Validation Class
require_once('verify_mail.class.php');

// the email to validate
$email = 'matteo.frassine@beniculturali.it';
// an optional sender
$sender = 'raptor@beniculturali.it';

print_r(verifyEmail($email, $sender, true));


?>
