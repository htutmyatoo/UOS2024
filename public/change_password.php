<?php 
session_start();
require '../private/dbconnection.php';

// if (!isset($_SESSION['forgot_password']) AND !isset($_SESSION['change_password'])) { // Check if the user is logged in
//     $_SESSION['login_error'] = "✖ Access Denied";
//     header('Location: index.php'); // Redirect to login page if not logged in
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
            <form class="container" id="passwordResetForm" action="../private/change_password_process.php" method="post" autocomplete="off">
                    <div style="background: #FF3535; margin-bottom: 15px; border-radius: 3px;">
                    <!-- error message -->
                        <?php
                            if (isset($_SESSION['password_reset_error'])) : ?>   
                            <p style="color: white; font-size: 12px;"><?= $_SESSION['password_reset_error']; unset($_SESSION['password_reset_error']);?></p>
                        <?php endif ?>
                    <!-- /error message -->
                    </div>
                    <!-- request current password -->
                    <?php
                        if (isset($_SESSION['change_password'])) : ?>   
                        <input type="password" placeholder="Your Current Password" name="current_pass" id="currnetPassword" required>
                    <?php endif ?>
                    <!-- /request current password -->
                    <input type="text" name="honeypot">
                    <input type="password" placeholder="Create New Password" name="create_pass" id="createNewPassword" oninput="checkPasswordStrength(this.value, document.getElementById('passwordMessage3'))" onfocus="focus1(this.value, document.getElementById('passwordMessage3'), document.getElementById('passwordMessage4'))" onblur="hide(document.getElementById('passwordMessage3'), document.getElementById('passwordMessage4'))" required>
                    <div class="password-message3" id="passwordMessage3"></div>
                    <input type="password" placeholder="Confirm New Password" name="confirm_pass" id="confirmNewPassword" oninput="confirmPassword(this.value, document.getElementById('passwordMessage4'), document.getElementById('createNewPassword'))" onfocus="focus2(this.value, document.getElementById('passwordMessage3'), document.getElementById('passwordMessage4'), document.getElementById('confirmNewPassword'))"  onblur="hide(document.getElementById('passwordMessage3'), document.getElementById('passwordMessage4'))" required>
                    <div class="password-message4" id="passwordMessage4"></div>              
                    <button type="submit" name="submit" class="submitbutton2" id="submitButton2">Submit</button>
                    <br />
                </form>

        </div>
    </div>

    <script><?php include 'assets/script.js'; ?></script> 
    
</body>
</html>
