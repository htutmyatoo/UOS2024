<?php
    declare(strict_types=1);
    session_start();
    
    try {
        // Track failed attempts in the session
        if (!isset($_SESSION['otp_retries'])) {
            $_SESSION['otp_retries'] = 0;
        }

        if(isset($_POST["submit"])){
            $otp1 = filter_var($_POST['otp1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp2 = filter_var($_POST['otp2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp3 = filter_var($_POST['otp3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp4 = filter_var($_POST['otp4'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp5 = filter_var($_POST['otp5'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp6 = filter_var($_POST['otp6'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $userEnteredCode = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;

            require 'dbconnection.php';
            require '../vendor/autoload.php';

            $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

            $key = "b7c76e5c41d3f20f6c89b50914fada595c961ebbf18d3f1ebf7867e3ea5b4c2a";
            $iv = "45d6f6b0929a832bbf73c9bcf38d817a";

            $decryptedKey = openssl_decrypt($_SESSION['attempt_key'], 'AES-256-CBC', $key, 0, $iv);

            if ($g->checkCode($decryptedKey , $userEnteredCode)) {

                $_SESSION['logged_userid'] = $_SESSION['attempt_userid'];
                $_SESSION['logged_user'] = $_SESSION['attempt_name'];
                $_SESSION['logged_email'] = $_SESSION['attempt_email'];
                
                unset($_SESSION['attempt_userid']);
                unset($_SESSION['attempt_name']);
                unset($_SESSION['attempt_email']);
                
                $_SESSION['logged_in'] = true; // Set a session variable to indicate login
                header('location: ' . DOMAIN . 'home.php'); 
              } else {
                $_SESSION['otp_retries']++;
                $_SESSION['attempt_logged_in'] = true;
                $_SESSION['totp_error'] = "Invalid TOTP.";
                header('location: ' . DOMAIN . 'totp.php');
                if ($_SESSION['otp_retries'] > 3) {
                    unset($_SESSION['otp_retries']);
                    unset($_SESSION['totp_error']);
                    $_SESSION['login_error'] = "Too Many OTP Attempts";
                    header('location: ' . DOMAIN . 'index.php');
                    die();
                } 
              }
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Error: " . $e->getMessage();
        header('location: ' . DOMAIN . 'index.php');
        die();
    }
?>