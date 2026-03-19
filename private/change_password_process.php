<?php
    // Check honeypot field
    if (!empty($_POST['honeypot'])) {
        exit("BOT SPAM ATTEMPT DETECTED!!");
    }

    session_start();
    
    try {
        require 'dbconnection.php';

        // Track failed attempts in the session
        if (!isset($_SESSION['retries'])) {
            $_SESSION['retries'] = 0;
        }

        if(isset($_POST["submit"])){
        
            $query = $conn->prepare('SELECT * FROM students WHERE id = :id');

            if(isset($_SESSION['change_password'])){
                $query->bindValue(':id', $_SESSION['logged_userid']);   
            }

            if(isset($_SESSION['forgot_password'])){
                $query->bindValue(':id', $_SESSION['forgot_password_userid']); 
            }

            $query->execute();
            $get_user = $query->fetch(PDO::FETCH_ASSOC);
            $database_password = $get_user['auth_words'];
            $old_password = $get_user['old_password'];

            $query = $conn->prepare("SELECT auth_words FROM students");
            $query->execute();
            $hashes = $query->fetchAll(PDO::FETCH_COLUMN);

            if(isset($_SESSION['change_password'])){
                $currentPassword = filter_var($_POST['current_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                if (!password_verify($currentPassword, $database_password)) {
                    $_SESSION['password_reset_error'] = "✖ Current password you entered is incorrect.";
                    header('location: ' . DOMAIN . 'change_password.php');
                    exit();
                }   
            }
            
            $createPassword = filter_var($_POST['create_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword = filter_var($_POST['confirm_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (password_verify($createPassword, $database_password)) {
                $_SESSION['password_reset_error'] = "✖ New password must not be same with current password.";
            }
            else if (password_verify($createPassword, $old_password)) {
                $_SESSION['password_reset_error'] = "✖ You cannot reuse an old password. Please choose a different one.";
            } 
            else {
                foreach ($hashes as $stored_hash){
                    if (password_verify($createPassword, $stored_hash)) {
                        $_SESSION["register_error"] = "✖ Password is already used";
                    }
                }
            }
            
            if (strlen($createPassword) < 9) {
                $_SESSION['password_reset_error'] = "✖ Password must be at least 9 characters long.";
            }   
            else if (!preg_match('/[a-z]/', $createPassword) || !preg_match('/[A-Z]/', $createPassword)) {
                $_SESSION['password_reset_error'] = "✖ Password must contain both uppercase and lowercase letters.";
            } 
            else if (!preg_match('/\d/', $createPassword)) {
                $_SESSION['password_reset_error'] = "✖ Password must contain at least one number.";
            } 
            else if (!preg_match('/[!@#$%^&*,.?":]/', $createPassword)) {
                $_SESSION['password_reset_error'] = "✖ Password must contain at least one symbol.";
            }
            else if ($createPassword !== $confirmPassword) {
                $_SESSION['password_reset_error'] = "✖ Passwords do not match.";
            }

            $createPassword = password_hash($createPassword, PASSWORD_DEFAULT);

            $query = $conn->prepare("UPDATE students SET auth_words = :new_password, old_password = :old_password WHERE id = :userid");
            $query->bindValue(":new_password", $createPassword);
            $query->bindValue(":old_password", $database_password);

            if (!isset($_SESSION['password_reset_error']) AND isset($_SESSION['forgot_password'])) {
                $query->bindValue(":userid", $_SESSION['forgot_password_userid']);
                $query->execute();

                $_SESSION['index_success'] = "Changed Password Successfully, login with new password.";
                header('location: ' . DOMAIN . 'index.php');
            }
            else if (!isset($_SESSION['password_reset_error']) AND isset($_SESSION['change_password'])){
                $query->bindValue(":userid", $_SESSION['logged_userid']);
                $query->execute();

                $_SESSION['home_success'] = "Password is changed successfully";
                header('location: ' . DOMAIN . 'home.php');
            } 
            else {
                $_SESSION['retries']++;
                header('location: ' . DOMAIN . 'change_password.php');
                if ($_SESSION['retries'] > 3) {
                    unset($_SESSION['retries']);
                    unset($_SESSION['password_reset_error']);
                    if (isset($_SESSION['forgot_password'])) {
                        $_SESSION['login_error'] = "Too Many Attempted";
                        header('location: ' . DOMAIN . 'index.php');
                        exit();
                    }
                    $_SESSION['login_error'] = "Too Many Attempted";
                    header('location: ' . DOMAIN . 'home.php');
                } 
            exit();
            }  
        }
    } catch (PDOException $e) {
        if (!isset($_SESSION['forgot_password'])) {
            $_SESSION['home_error'] = "Error: " . $e->getMessage();
            header('location: ' . DOMAIN . 'home.php');
            die();
        }
        $_SESSION['login_error'] = "Error: " . $e->getMessage();
        header('location: ' . DOMAIN . 'index.php');
        die();
    }
