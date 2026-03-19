<?php
session_start();

require 'dbconnection.php';

try {
    if (isset($_GET['email'])) {
        $_SESSION['forgot_password_email'] = filter_var($_GET['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        if (!(filter_var($_SESSION['forgot_password_email'], FILTER_VALIDATE_EMAIL))) {
            echo '<script>alert("✖ Email is invalid or Incorrect")</script>';
            echo '<script>history.back()</script>'; 
        } else {
    
            $query = $conn->prepare('SELECT * FROM students WHERE email = :email ');
            $query->bindValue(':email', $_SESSION['forgot_password_email'] ); 
            $query->execute();
            $result = $query->rowcount();
    
            if ($result === 1) {

                $get_user = $query->fetch(PDO::FETCH_ASSOC);
                $_SESSION['forgot_password_userid'] = $get_user['id'];
                $_SESSION['forgot_password_name'] = $get_user['username'];
                $_SESSION['sq_answer1'] = $get_user['sq_answer1'];
                $_SESSION['sq_answer2'] = $get_user['sq_answer2'];
                $_SESSION['sq_answer3'] = $get_user['sq_answer3'];

                $_SESSION['forgot_password'] = true;

                require_once 'send.php';
                header('location: ' . DOMAIN . 'otp.php');
                $conn = null;
                exit();
            }
            else if ($result === 0){
                $_SESSION['login_error'] = "✖  Your email is not registered";
            header('location: ' . DOMAIN . 'public/index.php');
            }
        }
    
    } else if (!isset($_GET['email'])){
        $_SESSION['login_error'] = "✖  Access Denied";
        header('location: ' . DOMAIN . 'public/index.php');
    }
} catch(PDOException $e) {
    $_SESSION['login_error'] = "Error: " . $e->getMessage();
    header('location: ' . DOMAIN . 'index.php');
    $conn = null;
    die();
}
