<?php 
    session_start();
    require 'dbconnection.php';
    if (!isset($_SESSION['attempt_logged_in'])) {
        header('Location: index.php');
        exit();
    }

    // Track failed attempts in the session
    if (!isset($_SESSION['resend_click'])) {
        $_SESSION['resend_click'] = 0;
    }
        
    $_SESSION['resend_click']++;

    require_once 'send.php';
    
    $_SESSION['attempt_logged_in'] = true;
    header('location: ' . DOMAIN . 'otp.php');

    if ($_SESSION['resend_click'] > 3) {
        session_unset();
        $_SESSION['login_error'] = "✖ Too Many Email Resend Detected";
        header('location: ' . DOMAIN . 'index.php');
        die();
    } 
