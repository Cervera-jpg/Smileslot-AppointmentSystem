<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exeption;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST["send"])){
    $mail = new PHPMailer(true);

    $mail -> isSMTP();
    $mail -> Host = 'smtp.gmail.com';
    $mail -> SMTPAuth = true;
    $mail -> username = 'jchrstn0401@gmail.com';
    $mail -> Password = 'pifbfykmfbivpzil';
    $mail -> SMTPSecure = 'ssl';
    $mail -> port = 465;

    $mail -> setFrom('jchrstn0401@gmail.com');

    $mail -> addAddress($_POST["$useremail"]);
    $mail -> isHTML(TRUE);
    
    $mail -> Subject = $_POST ["date"];
    $mail -> Body = $_POST ["scheduleid"];

    $mail -> send();


}