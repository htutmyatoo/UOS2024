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
            $userEnteredOtp = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;
            
            require 'dbconnection.php';
            require_once 'function.php';

            $max_retries = 3;
            if (verifyOtp($userEnteredOtp)) {
                unset($_SESSION['otp_retries']);

                if (isset($_SESSION['attempt_logged_in'])) { 
                    header('location: ' . DOMAIN . 'totp.php');
                    exit(); 
                }

                if (isset($_SESSION['register_email'])) {

                    require_once '../vendor/autoload.php';

                    $hash_pass = password_hash($_SESSION['createPassword'], PASSWORD_DEFAULT);

                    $key = "b7c76e5c41d3f20f6c89b50914fada595c961ebbf18d3f1ebf7867e3ea5b4c2a";
                    $iv = "45d6f6b0929a832b";
                    
                    $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
                    $_SESSION['secret'] = $g->generateSecret();
                    $encryptedSecret = openssl_encrypt($_SESSION['secret'], 'AES-256-CBC', $key, 0, $iv); 

                    $hash_answer1 = password_hash($_SESSION['sq_answer1'], PASSWORD_DEFAULT);
                    $hash_answer2 = password_hash($_SESSION['sq_answer2'], PASSWORD_DEFAULT);
                    $hash_answer3 = password_hash($_SESSION['sq_answer3'], PASSWORD_DEFAULT);

                    $query = $conn->prepare("INSERT INTO students (id, username, email, auth_words, secret_key, sq_answer1, sq_answer2, sq_answer3, old_password) VALUES (:id, :username, :email, :hash_pass, :secret_key, :sq_answer1, :sq_answer2, :sq_answer3, :old_password)");
                    $query->bindValue(':id',"");
                    $query->bindValue(':username', $_SESSION['register_name']);
                    $query->bindValue(':email', $_SESSION['register_email']);
                    $query->bindValue(':hash_pass', $hash_pass);
                    $query->bindValue(':secret_key', $encryptedSecret);
                    $query->bindValue(':sq_answer1', $hash_answer1);
                    $query->bindValue(':sq_answer2', $hash_answer2);
                    $query->bindValue(':sq_answer3', $hash_answer3);
                    $query->bindValue(':old_password', NULL);
                    $query->execute();
                    
                    header('location: ' . DOMAIN . 'scan_code.php');
                    exit();
                }

                if (isset($_SESSION['forgot_password']) || isset($_SESSION['change_password'])) {
                    header('location: ' . DOMAIN . 'security_questions.php'); 
                    exit();
                }
                
              } else {
                $_SESSION['otp_retries']++;
                $_SESSION['otp_error'] = "✖ Invalid or already used OTP.";
                header('location: ' . DOMAIN . 'otp.php');
                if ($_SESSION['otp_retries'] > $max_retries) {
                    unset($_SESSION['otp_retries']);
                    unset($_SESSION['otp_error']);
                    $_SESSION['login_error'] = "✖ Too Many OTP Attempts";
                    header('location: ' . DOMAIN . 'index.php');
                    die();
                } 
              }
        }
    } catch (PDOException $e) {
        $_SESSION['otp_error'] = "Error: " . $e->getMessage();
        header('location: ' . DOMAIN . 'index.php');
        die();
    }
