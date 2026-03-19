<?php 
session_start();

require '../private/dbconnection.php';

// if (!isset($_SESSION['forgot_password']) AND !isset($_SESSION['logged_in'])) {
//     $_SESSION['login_error'] = "✖ Access Denied";
//     header('Location: index.php');
//     exit();
// }
// ?>

<!DOCTYPE html>
<html>
<?php include_once '../private/header.php'; ?>
<body>

    <div class="container">
        <div class="page">
            <div class="logo">
                <img src="assets/images/UoS_Logo_BLACK.png" alt="University of Sunderland Logo" style="width: 175px">
            </div>
            <h4>Answer the Security Questions:</h4>
            <p style="font-size: 12px">to confirm the account owner is really you.</p>
            <form class="container" id="securityForm" action="../private/security_answer_verification.php" method="post" autocomplete="off">
                <div style="background: #FF3535; margin-bottom: 15px; border-radius: 3px;">
                <!-- error message -->
                    <?php
                    if (isset($_SESSION['sq_error'])) : ?>   
                    <p style="color: white; font-size: 10px;"><?= $_SESSION['sq_error']; unset($_SESSION['sq_error']);?></p>
                <?php endif ?>
                <!-- /error message -->
                </div>
                <input type="text" name="honeypot">
                <input type="color" name="sq1" id="sq1" value="#00335C" style="width:100%;">
                <input type="text" placeholder="Street name you grew up on?" name="sq2" id="sq2" maxlength="35" required>
                <input type="text" placeholder="The first project you did in last year?" name="sq3" id="sq3" maxlength="35" required>
                <button type="submit" name="submit" class="submitbutton2" id="submitButton2" style="margin-top: 0%; margin-bottom:30px;">Submit</button>
            </form>

        </div>
    </div>

    <script><?php include 'script.js'; ?></script>    

</body>
</html>
