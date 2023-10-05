<?php

use phpmailer\PHPMailer\PHPMailer;
use phpmailer\PHPMailer\SMTP;
use phpmailer\PHPMailer\Exception;

require './src/Exception.php';
require './src/PHPMailer.php';
require './src/SMTP.php';
$mail = new PHPMailer();

$mail->PluginDir = "includes/";
$mail->Mailer = "smtp";
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = "alzap1479@gmail.com"; 
  $mail->Password = "vdfygquqjweidpxq";

  $mail->From = "estebanavila182@gmail.com";
  $mail->FromName = "PRUEBAS 2022";
  $mail->Timeout=30;


  $mail->AddAddress("alzap1479@gmail.com");



  $mail->Subject = "Prueba de phpmailer";
  $mail->Body = "<b>Mensaje de prueba mandado con phpmailer en formato html</b>";

  $mail->AltBody = "Mensaje de prueba mandado con phpmailer en formato solo texto";

  $exito = $mail->Send();


  $intentos=1; 
  while ((!$exito) && ($intentos < 5)) {
	sleep(5);
     	//echo $mail->ErrorInfo;
     	$exito = $mail->Send();
     	$intentos=$intentos+1;	
	
   }


   if(!$exito)
   {
	echo "Problemas enviando correo electrónico a ".$valor;
	echo "<br/>".$mail->ErrorInfo;	
   }
   else
   {
	echo "Mensaje enviado correctamente";
   } 
?>