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

            $name = filter_var($_POST['r_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $createPassword = filter_var($_POST['create_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword = filter_var($_POST['confirm_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sq_answer1 = filter_var($_POST['sq1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sq_answer2 = filter_var($_POST['sq2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sq_answer3 = filter_var($_POST['sq3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
                if (empty($name) OR empty($email) OR empty($createPassword) OR empty($confirmPassword) OR empty($sq_answer2) OR empty($sq_answer3)) {
                    $_SESSION['register_error'] = "All fields are required" ;
                } 
                else if ($sq_answer1 === "#00335c"){
                    $_SESSION['register_error'] = "Choose Your Favourite Colour Code" ;
                } 
                else {
                    $allowed_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";
                    $reserved_words = ["admin", "administrator", "root", "support", "system"];
                    $malicious_patterns = ["/script/i", "/iframe/i", "/alert/i"];

                    //Validation for username
                    if (strlen($name) < 6 || strlen($name) > 30)   {
                        $_SESSION['register_error'] = "✖ Username must be between 6 and 30 characters long" ;
                    }
                    else if (!preg_match("/[^0-9]/", $name)) {
                        $_SESSION['register_error'] = "✖ Username must not be number only" ;
                    }
                    else if (preg_match('/[{(!@#$%^&*,.?"\':;)}]/', $name)){
                        $_SESSION['register_error'] = "✖ Username must contain letters and numbers only." ;
                    } else if (preg_match('/^[0-9]+[a-zA-Z]*$/', $name)) {
                        $_SESSION['register_error'] = "✖ Username cannot start with number" ;
                    }
                    else if (strpbrk($name, $allowed_chars) !== $name) {
                        $_SESSION['register_error'] = "✖ Username contains prohibited characters." ;
                    }
                    else if (in_array($name, $reserved_words)) {
                        $_SESSION['register_error'] = "✖ Username contains a reserved word." ;
                    }
                    // Validation for email
                    else if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
                        $_SESSION['register_error'] = "✖ Email is invalid or incorrect";
                    }
                    // Validation for password 
                    else if (strlen($createPassword) < 9) {
                        $_SESSION['register_error'] = "✖ Password must be at least 9 characters long.";
                    }   
                    else if (!preg_match('/[a-z]/', $createPassword) || !preg_match('/[A-Z]/', $createPassword)) {
                        $_SESSION['register_error'] = "✖ Password must contain both uppercase and lowercase letters.";
                    } 
                    else if (!preg_match('/\d/', $createPassword)) {
                        $_SESSION['register_error'] = "✖ Password must contain at least one number.";
                    } 
                    else if (!preg_match('/[!@#$%^&*,.?":]/', $createPassword)) {
                        $_SESSION['register_error'] = "✖ Password must contain at least one symbol.";
                    }
                    else if ($createPassword !== $confirmPassword) {
                        $_SESSION['register_error'] = "✖ Passwords do not match.";
                    }
                    else {
                        foreach ($malicious_patterns as $pattern) {
                            if (preg_match($pattern, $name || preg_match($pattern, $email) || preg_match($pattern, $createPassword) || preg_match($pattern, $sq_answer1) || preg_match($pattern, $sq_answer2) || preg_match($pattern, $sq_answer3))) {
                                $_SESSION['register_error'] = "✖ User inputs contain potentially malicious content." ;
                            }
                        }
                        // check if username or email or password already exists
                        $query = $conn->prepare("SELECT * FROM students WHERE username= '$name' ");
                        $query->execute();
                        $query->fetchAll(PDO::FETCH_ASSOC);
                        $get_username = $query->rowcount();

                        $query = $conn->prepare("SELECT * FROM students WHERE email= '$email' ");
                        $query->execute();
                        $query->fetchAll(PDO::FETCH_ASSOC);
                        $get_email = $query->rowcount();

                        $query = $conn->prepare("SELECT auth_words FROM students");
                        $query->execute();
                        $hashes = $query->fetchAll(PDO::FETCH_COLUMN);

                        if ($get_username > 0) {
                            $_SESSION['register_error'] = "✖ Username is already exist";            
                        } else if ($get_email > 0) {
                            $_SESSION['register_error'] = "✖ Email is already Registered";             
                        } else{
                            foreach ($hashes as $stored_hash){
                                if (password_verify($createPassword, $stored_hash)) {
                                    $_SESSION["register_error"] = "✖ Password is already used";
                                }
                            }
                        }
                    }
                }
        }
        else {
            $_SESSION['register_error'] = "✖ Must Success Human Verification." ;
        }

        if (!isset($_SESSION['register_error'])) {
            $_SESSION['register_name'] = $name;
            $_SESSION['register_email'] = $email;
            $_SESSION['createPassword'] = $createPassword;
            $_SESSION['sq_answer1'] = $sq_answer1;
            $_SESSION['sq_answer2'] = $sq_answer2;
            $_SESSION['sq_answer3'] = $sq_answer3;

            require_once 'send.php';
            header('location: ' . DOMAIN . 'otp.php');
            $conn = null;
        } 
        else if (isset($_SESSION['register_error'])) {
            $_SESSION['register_retries']++;  
                
            if ($_SESSION['register_retries'] > 10) {
                unset($_SESSION['register_retries']);
                $conn = null;
                sleep(1800); //30 mins delay
                $_SESSION['register_error'] = "Too Many Registration Attempted";
            }   
            header('Location: ' . DOMAIN . 'index.php');
            $conn = null;
            die();
        }
    }
    else {
        //if button was not clicked
        $_SESSION['register_error'] = "✖ Human verification is required." ;
        header('location: ' . DOMAIN . 'index.php');
        exit();
    }  
} catch(PDOException $e) {
    $_SESSION['register_error'] = "Error: " . $e->getMessage();
    header('location: ' . DOMAIN . 'index.php');
    die();
}    
      

