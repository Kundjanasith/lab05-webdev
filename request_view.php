<?php
ob_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
echo $_SERVER['REQUEST_METHOD'];
if($_SERVER['REQUEST_METHOD']=="POST"){
    echo "hello2";
    $toEmail = $_POST['request_email'];
    $Username = getenv('EMAIL_USERNAME');
    $Password = getenv('EMAIL_PASSWORD');
    $SMTP_HOST = getenv('SMTP_HOST');
$mail = new PHPMailer(); 
$mail->IsSMTP(); 
$mail->SMTPDebug = 0; 
$mail->SMTPAuth = true; 
$mail->SMTPSecure = 'ssl'; 
$mail->Host = $SMTP_HOST;
$mail->Port = 465; 
$mail->IsHTML(true);
$mail->Username =$Username;
$mail->Password = $Password;
$mail->SetFrom('dreamhome_5710545023@ku.th');
$mail->Subject = "Dreamhome";
$mail->Body = "Your request is now on the waiting list and a staff (".$_POST['staff_name'].") will be schedule the visit for viewing later.";
$mail->AddAddress($toEmail);
// echo $_POST['staff_name'];
 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
    header("Location: request_success.php");
    exit();
 }
}
header("Location: index.php");
exit();
?> 