<?php
session_start();

if (!isset($_SESSION['logged_in'])) { // Check if the user is logged in
    $_SESSION['login_error'] = "✖ You need to sign in";
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

unset($_SESSION['attempt_logged_in']);
?>

<!DOCTYPE html>
<html>

<?php include_once '../private/header.php'; ?>

<body>
    <div class="container">
        <div class="page">
            <div class="logo">
                <img src="assets/images/UoS_Logo_BLACK.png" alt="University of Sunderland Logo" style="width: 175px">
            </div>
              <form class="container" id="loginForm" action="" method="post" autocomplete="off">  
                <?php if (isset($_SESSION['logged_user'])) : ?>  
                    <h2>Welcome, <?= $_SESSION['logged_user'];?></h2>
                <?php endif ?>
                <!-- <hr style="width: 100%;"> -->
                <!-- success and error message --> 
                <?php
                    if (isset($_SESSION['home_success'])) : ?>  
                    <div id="alert" style="background: #00335C; margin-bottom: 15px; border-radius: 3px;"> 
                    <p style="color: white; font-size: 10px;"><?= $_SESSION['home_success']; unset($_SESSION['home_success']);?></p>
                    </div>
                    <?php elseif (isset($_SESSION['home_error'])) : ?>
                    <div id="alert" style="background: #FF3535; margin-bottom: 15px; border-radius: 3px;">       
                    <p style="color: white; font-size: 12px;"><?= $_SESSION['home_error']; unset($_SESSION['home_error']);?></p>
                    </div>
                <?php endif ?>
                <!-- \success and error message --> 
                <div style="text-align:left; margin-left:12%">
                <?php if (isset($_SESSION['logged_userid'])) : ?>  
                    <p style="font-size: 15px;">User ID: <?= $_SESSION['logged_userid'];?></p>
                <?php endif ?>
                <?php if (isset($_SESSION['logged_user'])) : ?>  
                    <p style="font-size: 15px;">Username: <?= $_SESSION['logged_user'];?></p>
                <?php endif ?>
                <?php if (isset($_SESSION['logged_email'])) : ?>  
                    <p style="font-size: 15px;">Email Address: <?= $_SESSION['logged_email'];?></p>
                <?php endif ?>
                
                <div class="forget-id" style="text-align:justify;">
                    <a href="security_questions.php">Change Password?</a>
                </div>
                </div>
                    <button class="submitbutton" id="submitButton" formaction="../private/logout.php" style="margin: 15% 25% 10% 25%">Logout</button>
                </form>
        </div>
    </div>

    <br />
    <script>
    const alertMessage = document.getElementById('alert');
        if (alertMessage) {
            setTimeout(() => {
                alertMessage.style.display = 'none';
        }, 5000); // 10 seconds in milliseconds
    }
    <?php include 'assets/script.js'; ?>
    </script>
</body>

</html>