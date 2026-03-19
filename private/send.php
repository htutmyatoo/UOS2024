<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->SMTPDebug = 2;       
    $mail->isSMTP(); //Send using SMTP
    $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
    $mail->SMTPAuth   = true; //Enable SMTP authentication
    $mail->Username   = 'e37872045@gmail.com'; //SMTP username
    $mail->Password   = 'jlpq mdyk nmtg jkcn'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('e37872045@gmail.com', 'UOS2024');
    $mail->isHTML(true); 

    require_once 'function.php';
    $generatedOtp = generateOtp();

    if (isset($_SESSION['attempt_logged_in'])) { // Check if the user is logged in
        $mail->addAddress($_SESSION['attempt_email'], $_SESSION['attempt_name']);
        $mail->Subject = 'Login Verification';
        $mail->Body    = "<p>Hi! " . $_SESSION['attempt_name'] . ",</p><br>" . "<p>Your OTP Code is " . "[" . $generatedOtp . "]" . ". OTP (One Time Password) is for your account security purpose. DO NOT SHARE it with anyone.</p><br>" . "Best Regards,<br>" . "UOS2024";
        $mail->AltBody = "Hi! " . $_SESSION['attempt_name'] . "," . "\r\nYour OTP Code is " . "[" . $generatedOtp . "]" . ". OTP (One Time Password) is for your account security purpose. DO NOT SHARE it with anyone" . "\r\nBest Regards," . "\r\nUOS2024";
    }

    if (isset($_SESSION['register_email'])) {
        $mail->addAddress($_SESSION['register_email'], $_SESSION['register_name']);
        $mail->Subject = 'Email Verification Before Register';
        $mail->Body    = "<p>Hi! " . $_SESSION['register_name'] . ",</p><br>" . "<p>Use this token:  " . "[" . $generatedOtp . "]" . " to verify your email address.</p><br>" . "Best Regards,<br>" . "UOS2024";
    }

    if (isset($_SESSION['forgot_password'])) {
        $mail->addAddress($_SESSION['forgot_password_email'], $_SESSION['forgot_password_name']);
        $mail->Subject = 'Password Reset Code';
        $mail->Body    = "<p>Hi! " . $_SESSION['forgot_password_name'] . ",</p><br>" . "<p>Use this token:  " . "[" . $generatedOtp . "]" . " to reset the password.</p><br>" . "<p>If this request did not come from you, change your account password immediately to prevent futher unauthorized access.</p><br>" .
        "Best Regards,<br>" . "UOS2024";
    }

    if (isset($_SESSION['change_password'])) {
        $mail->addAddress($_SESSION['logged_email'], $_SESSION['logged_user']);
        $mail->Subject = 'Confirmation for Password Changing';
        $mail->Body    = "<p>Hi! " . $_SESSION['logged_user'] . ",</p><br>" . "<p>Use this token:  " . "[" . $generatedOtp . "]" . " to change the password.</p><br>" . "Best Regards,<br>" . "UOS2024";
    }
    $mail->send();

} catch (Exception $e) {
    // $_SESSION['login_error'] = $_SESSION['attempt_logged_in'] . $_SESSION['register_email'] .  $_SESSION['forgot_password_email'] . $_SESSION['logged_email'];
    $_SESSION['login_error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    header('location: ' . DOMAIN . 'index.php');
    die();
}
