<?php 
session_start();

if (!isset($_SESSION['attempt_logged_in']) AND !isset($_SESSION['register_email']) AND !isset($_SESSION['forgot_password'])) {
    $_SESSION['login_error'] = "You need to sign in";
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<?php include_once '../private/header.php'; ?>

<body>
    <div class="otp-container">
    <div>
        <div class="logo">
            <img src="assets/images/UoS_Logo_BLACK.png" alt="University of Sunderland Logo" style="width: 175px">
        </div>    
        <h2>Enter Email Verification Code</h2>
        <form action="../private/otp_verification.php" method="post">
            <div id="otpErrorMessage" style="background: #FF3535; border-radius: 3px;">
            <!-- error message -->
                <?php
                    if (isset($_SESSION['otp_error'])) : ?>   
                    <p style="color: white; font-size: 10px;"><?= $_SESSION['otp_error']; unset($_SESSION['otp_error']);?></p>
                <?php endif ?>
            <!-- /error message -->
            </div>
            <div class="otp-fields">
            <input type="text" name="otp1" maxlength="1" autocomplete="off" autofocus required>
            <input type="text" name="otp2" maxlength="1" autocomplete="off" required>
            <input type="text" name="otp3" maxlength="1" autocomplete="off" required>
            <input type="text" name="otp4" maxlength="1" autocomplete="off" required>
            <input type="text" name="otp5" maxlength="1" autocomplete="off" required>
            <input type="text" name="otp6" maxlength="1" autocomplete="off" required>
            </div>
            <p>OTP Code is sent to 
                <!-- login mail -->
                <?php
                    if (isset($_SESSION['attempt_email'])) : ?>   
                    <strong id="strong-text"><?= $_SESSION['attempt_email'];?></strong>
                <?php endif ?>
                <!-- /login mail -->
                <!-- register mail -->
                <?php
                    if (isset($_SESSION['register_email'])) : ?>   
                    <strong id="strong-text"><?= $_SESSION['register_email'];?></strong>
                <?php endif ?>
                <!-- /register mail -->
                <!-- forgot mail -->
                <?php
                    if (isset($_SESSION['forgot_password_email'])) : ?>   
                    <strong id="strong-text"><?= $_SESSION['forgot_password_email'];?></strong>
                <?php endif ?>
                <!-- /forgot mail -->
            </p>
            <a href="../private/resend.php">Resend Code</a>
            <button type="submit" name="submit" class="verifybutton">Verify</button>
        </form>
    </div>
    </div>
    <script>
         <?php include 'script.js'; ?>
    </script>
</body>

</html>