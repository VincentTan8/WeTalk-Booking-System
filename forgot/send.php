<?php
if(!isset($_SESSION)){
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 365);	
session_start();
ob_start();
}
?>


<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
 
require '../PHPMailer/src/Exception.php'; 
require '../PHPMailer/src/PHPMailer.php'; 
require '../PHPMailer/src/SMTP.php'; 
 $mail = new  PHPMailer();

 //smtp.office365.com
 //STARTTLS
 //BMBE@dti.gov.ph
 //DTI2022!
 
 
  
 
/*$mail->isSMTP();                     
$mail->Host = 'smtp.gmail.com';       
$mail->Port =587;                    
 $mail->SMTPAuth = true;          
 $mail->SMTPSecure = 'tsl';           
  $mail->Username = 'bmbenoreply@gmail.com';   
 $mail->Password = 'vdmbomszxzcapnmn';*/
 
/*
  $mail->isSMTP();                     
$mail->Host = 'smtp.gmail.com';       
$mail->Port =587;                    
 $mail->SMTPAuth = true;          
 $mail->SMTPSecure = 'tsl';           
  $mail->Username = 'm.onesolutions.noreply@gmail.com';   
 $mail->Password = 'cuny wkza bghv ipqx';   */
 
 
 $mail->isSMTP();                     
$mail->Host = 'smtp.office365.com';       
$mail->Port =587;                    
$mail->SMTPAuth = true;          
 $mail->SMTPSecure = 'tsl';           
  $mail->Username = 'no-reply@wetalk.com';   
 $mail->Password = 'T%048848824367ata'; 

 
 

//PHPMailer::ENCRYPTION_STARTTLS

//$mail->SMTPDebug = 3;

 
 //echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";
// Sender info 
//$mail->SMTPKeepAlive = true;
$mail->setFrom('no-reply@wetalk.com', 'WETALK-ONLINE'); 
$mail->addReplyTo('no-reply@wetalk.com', 'WETALK-ONLINE'); 
 
//$_SESSION['your_email']='contact@wetalk.com';
 
$customer=$_SESSION['your_email'];

$receiver=$_SESSION['your_email'];


//$receiver='shef735@gmail.com';
// Add a recipient 
$mail->addAddress($receiver,'WETALK-CONTACT'); 
 
//$mail->addCC('cc@example.com'); 
//$mail->addBCC('bcc@example.com'); 
 
// Set email format to HTML 
$mail->isHTML(true); 
 
 
 $subject= 'OTP REQUEST'; 
// Mail subject 

if(!isset($bodyContent)) {
	$bodyContent='';
}

 
    $otp = implode('', $_SESSION['otp']);
    
// Mail body content 
 
$bodyContent = "Your OTP for password reset is: $otp\n\nThis OTP is valid for 10 minutes.";
 

/////////////////////////////////////////


$mail->Subject = $subject;					
$mail->Body = $bodyContent; 
 
// Send email 
if(!$mail->send()) { 
  //echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
} else { 
 //echo 'Message has been sent.'; 

 
} 

$mail->clearAddresses();
    $mail->clearAttachments();
 
?>