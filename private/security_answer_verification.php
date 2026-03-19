<?php
    // Check honeypot field
    if (!empty($_POST['honeypot'])) {
        exit("BOT SPAM ATTEMPT DETECTED!!");
    }
    
    session_start();

    try {
        require 'dbconnection.php';

        // Track failed attempts in the session
        if (!isset($_SESSION['sq_retries'])) {
            $_SESSION['sq_retries'] = 0;
        }

        if(isset($_POST["submit"])){
            $user_answer1 = filter_var($_POST['sq1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user_answer2 = filter_var($_POST['sq2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user_answer3 = filter_var($_POST['sq3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($user_answer1 === "#00335c"){
                    $_SESSION['sq_error'] = "Choose Your Favourite Colour Code" ;
                } 
            else if (!$user_answer2 || !$user_answer3) {
                $_SESSION['sq_error'] = "✖ All field are required";
            }else{

                $score = 0;

                $query = $conn->prepare('SELECT * FROM students WHERE username = :username');

                if (isset($_SESSION['logged_in'])) {
                    $query->bindValue(':username', $_SESSION['logged_user']); 
                }

                if (isset($_SESSION['forgot_password'])) {
                    $query->bindValue(':username', $_SESSION['forgot_password_name']); 
                }
                
                $query->execute();
                $get_user = $query->fetch(PDO::FETCH_ASSOC);
                $db_answer1 = $get_user['sq_answer1'];
                $db_answer2 = $get_user['sq_answer2'];
                $db_answer3 = $get_user['sq_answer3'];

                if (password_verify($user_answer1, $db_answer1)) {
                    $score++;
                }
                if (password_verify($user_answer2, $db_answer2)) {
                    $score++;
                }
                if (password_verify($user_answer3, $db_answer3)) {
                    $score++;
                }
                
                if ($score === 3){
                    if (!isset($_SESSION['forgot_password'])) {
                        $_SESSION['change_password'] = true;
                    }
                    header('location: ' . DOMAIN . 'change_password.php');
                } 
                else {
                    $_SESSION['sq_retries']++;
                    $_SESSION['sq_error'] = '✖ All answers must be correct';
                    header('location: ' . DOMAIN . 'security_questions.php');
                    if ($_SESSION['sq_retries'] > 3) {
                        unset($_SESSION['sq_retries']);
                        unset($_SESSION['sq_error']);
                        $_SESSION['login_error'] = "Too Many Security Answers Attempted";
                        header('location: ' . DOMAIN . 'index.php');
                    } 
                exit();
                }  
            }
        }
    } catch (PDOException $e) {
        if (!isset($_SESSION['forgot_password'])) {
            $_SESSION['home_error'] = "Error: " . $e->getMessage();
            header('location: ' . DOMAIN . 'home.php');
        }
        $_SESSION['login_error'] = "Error: " . $e->getMessage();
        header('location: ' . DOMAIN . 'index.php');
        die();
    }
