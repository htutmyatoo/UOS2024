<?php
try {
    // Check honeypot field
    if (!empty($_POST['honeypot'])) {
        exit("BOT SPAM ATTEMPT DETECTED!!");
    }

    // session start to use session variables
    session_start();
    require 'dbconnection.php';

    // Track failed attempts in the session
    if (!isset($_SESSION['login_retries'])) {
        $_SESSION['login_retries'] = 0;
    }

    // Check submit or not and ensure g-recaptcha has response
    if(isset($_POST["submit"]) && !empty($_POST['g-recaptcha-response'])){
        
        require_once 'g_recaptcha.php';
        
        //Check g-recaptcha is success
        if ($responseData->success) {
            
            //Assign malicious content
            $reserved_words = ["admin", "administrator", "root", "support", "system"];
            $malicious_patterns = ["/php/i", "/script/i", "/iframe/i", "/alert/i"];

            //Obtain userinputs by sanitizing full special characters
            $username = filter_var($_POST['l_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var($_POST['pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (!$username || !$password) {
                $_SESSION['login_error'] = "✖ All fields are required";
            }
            // username validation
            else if (in_array($username, $reserved_words)) {
                $_SESSION['login_error'] = "✖ Username contains a reserved word." ;
            }
            else{
                foreach ($malicious_patterns as $pattern) {
                    if (preg_match($pattern, $username) || preg_match($pattern, $password)) {
                    $_SESSION['login_error'] = "✖ Username contains potentially malicious content." ;
                    }
                }
            }
        }
        else {
            //if not success
            $_SESSION['login_error'] = "✖ Must Success Human Verification." ;
        }
    }
    else {
        //if button was not clicked
        $_SESSION['login_error'] = "✖ Human verification is required." ;
    }

    if (!isset($_SESSION['login_error'])) {
        $query = $conn->prepare('SELECT * FROM students WHERE username = :username ');
        $query->bindValue(':username', $username); 
        $query->execute();
        $result = $query->rowcount();

        if ($result === 1) {
        $get_user = $query->fetch(PDO::FETCH_ASSOC);
        $database_password = $get_user['auth_words'];
           
            //compare form password wth database password
            if (password_verify($password, $database_password)) {  
                $_SESSION['attempt_logged_in'] = true;
                $_SESSION['attempt_userid'] = $get_user['id'];
                $_SESSION['attempt_name'] = $get_user['username'];
                $_SESSION['attempt_email'] = $get_user['email'];
                $_SESSION['attempt_key'] = $get_user['secret_key'];
        
                require_once 'send.php';
                header('location: ' . DOMAIN . 'otp.php');
                exit();     
            } else {
                $_SESSION['login_error'] = "✖ Invalid login credentials."; 
            }
        } else { 
            $_SESSION['login_error'] = "✖ Invalid login credentials."; 
        }
    }
    
    if (isset($_SESSION['login_error'])) {

        $_SESSION['login_retries']++;  
        if ($_SESSION['login_retries'] > 3) {
            unset($_SESSION['login_retries']);
            $conn = null;
            sleep(30);
            $_SESSION['login_error'] = "Too Many Login Attempted";
        }
        unset($_SESSION['attempt_logged_in']);
        header('Location: ' . DOMAIN . 'index.php');
        die();
    }  
} catch (PDOException $e) {
    $_SESSION['login_error'] = "Error: " . $e->getMessage();
    header('Location: ' . DOMAIN . 'index.php');
    die();
}
