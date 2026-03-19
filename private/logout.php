<?php  
session_start(); 
session_unset();
session_destroy();

require "dbconnection.php";
header('location: ' . DOMAIN . 'index.php' );

die();
