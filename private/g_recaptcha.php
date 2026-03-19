<?php
    $secret = '6LdhEl4pAAAAAK9uGJnMXjY6ykRhz9a_AWD7Qtnk';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);