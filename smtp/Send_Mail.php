<?php
function Send_Mail($to,$subject,$body)
{
require 'class.phpmailer.php';
$from       = "ale.hpineda@gmail.com";
$mail       = new PHPMailer();
$mail->IsSMTP(true);            // use SMTP
$mail->IsHTML(true);
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       =  465;                    // set the SMTP port
$mail->Username   = "ale.hpineda";  // SMTP  username
$mail->Password   = "h0m3l355";  // SMTP password
$mail->SetFrom($from, 'From Club Santos Mx');
$mail->AddReplyTo($from,'From Club Santos Mx');
$mail->Subject    = $subject;
$mail->MsgHTML($body);
$address = $to;
$mail->AddAddress($address, $to);
$mail->Send();   
}
?>
