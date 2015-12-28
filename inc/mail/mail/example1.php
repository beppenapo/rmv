<?php
    
/**
 * Example 1
 * Validate a single Email via SMTP
 */


// include SMTP Email Validation Class
require_once('smtp_validateEmail.class.php');

// the email to validate
$email = 'matteo.frassine@beniculturali.it';
// an optional sender
$sender = 'raptor@beniculturali.it';
// instantiate the class
$SMTP_Validator = new SMTP_validateEmail();
// turn on debugging if you want to view the SMTP transaction
$SMTP_Validator->debug = true;
// do the validation
$results = $SMTP_Validator->validate(array($email), $sender);
// view results
echo $email.' is '.($results[$email] ? 'valid' : 'invalid')."\n";

// send email? 
if ($results[$email]) {
  die('ok');
  //mail($email, 'Confirm Email', 'Please reply to this email to confirm', 'From:'.$sender."\r\n"); // send email
} else {
  die('mail non trovata');
}


?>
