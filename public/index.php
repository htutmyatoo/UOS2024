<?php
session_start();

if (isset($_SESSION['logged_in'])) { 
    $_SESSION['home_error'] = "You are already sign in";
    header('Location: home.php'); 
    exit();
}
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
            <br />
            <!-- Tab links -->
            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'Login')" id="defaultOpen">Login</button>
                <button class="tablinks"  id="notDefault" onclick="openCity(event, 'Register'); hide()">Register</button>
            </div>

            <!-- Tab content -->
            <div id="Login" class="tabcontent">
                <form class="container" id="loginForm" action="../private/login.php" method="post" autocomplete="off" style="margin-top: 25px">
                    <input type="text" placeholder="Username" name="l_name" id="username" maxlength="" required>
                    <input type="password" placeholder="Password" name="pass" id="passwordInput" required>
                    <!-- Password toggle button with open and close eye images -->
                    <img id="eyeIcon" src="assets/images/eyeOpen.png" alt="Open Eye" width="20px" onclick="togglePasswordVisibility()">
                    <input type="text" name="honeypot">
                    <!-- success and error message -->    
                    <?php
                        if (isset($_SESSION['index_success'])) : ?>  
                        <div id="error-message" style="background: #00335C; margin-bottom: 15px; border-radius: 3px;"> 
                        <p style="color: white; font-size: 10px;"><?= $_SESSION['index_success']; unset($_SESSION['index_success']);?></p>
                        </div>
                        <?php elseif (isset($_SESSION['login_error'])) : ?>
                        <div id="error-message" style="background: #FF3535; margin-bottom: 15px; border-radius: 3px;">       
                        <p style="color: white; font-size: 12px;"><?= $_SESSION['login_error']; unset($_SESSION['login_error']);?></p>
                        </div>
                    <?php endif ?>
                    <!-- /success and error message -->  
                    <div class="recaptcha-container" style="width: 50%; text-align:left;">
                        <div class="g-recaptcha" data-sitekey="6LdhEl4pAAAAAME5PDYrljGzRe3Q1SktuuurEYqI"></div>
                    </div> 
                    <button type="submit" name="submit" class="submitbutton" id="submitButton">Sign In</button>
                </form>
                <div class="forget-id" style="margin-top: 15px;">
                    <a href="#" onclick="promptForEmail()">Forgot password?</a>
                </div>
            </div>
            
            <div id="Register" class="tabcontent">
                <form class="container" id="registerForm" action="../private/register.php" method="post" autocomplete="off">
                    <!-- error message -->
                    <div id="error-message" style="background: #FF3535; margin-bottom: 15px; border-radius: 3px;">
                        <?php
                            if (isset($_SESSION['register_error'])) : ?>   
                            <p style="color: white; font-size: 12px;"><?= $_SESSION['register_error']; unset($_SESSION['register_error']);?></p>
                        <?php endif ?>
                    </div>
                    <!-- /error message -->
                    <input type="text" placeholder="Username" name="r_name" id="username" maxlength="30" required>
                    <input type="text" placeholder="Email Address" name="email" id="email" maxlength="35" required>
                    <input type="text" name="honeypot">
                    <input type="password" placeholder="Create Password" name="create_pass" id="createPasswordInput" oninput="checkPasswordStrength(this.value, document.getElementById('passwordMessage'))" onfocus="focus1(this.value, document.getElementById('passwordMessage'), document.getElementById('passwordMessage2'))" onblur="hide(document.getElementById('passwordMessage'), document.getElementById('passwordMessage2'))" required>
                    <div class="password-message" id="passwordMessage"></div>
                    <input type="password" placeholder="Confirm Password" name="confirm_pass" id="confirmPasswordInput" oninput="confirmPassword(this.value, document.getElementById('passwordMessage2'), document.getElementById('createPasswordInput'))" onfocus="focus2(this.value, document.getElementById('passwordMessage'), document.getElementById('passwordMessage2'), document.getElementById('confirmPasswordInput'))"  onblur="hide(document.getElementById('passwordMessage'), document.getElementById('passwordMessage2'))" required>
                    <div class="password-message2" id="passwordMessage2"></div>
                    <input type="color" name="sq1" id="sq1" value="#00335C" style="width:100%;">
                    <input type="text" placeholder="Street name you grew up on?" name="sq2" id="sq2" maxlength="35" required>
                    <input type="text" placeholder="The first project you did in last year?" name="sq3" id="sq3" maxlength="35" required>
                    <div class="recaptcha-container" style="width: 50%; text-align:left;">
                        <div class="g-recaptcha" data-sitekey="6LdhEl4pAAAAAME5PDYrljGzRe3Q1SktuuurEYqI"></div>
                    </div> 
                    <button type="submit" name="submit" class="submitbutton2" id="submitButton2">Sign Up</button>
                    <br />
                </form>
                <div class="forget-id">
                    <a href="https://github.com/9teenmine" target="_blank">© 2024 Htut Myat Oo</a>
                </div>
            </div>`

        </div>
    </div>

    <script><?php include 'assets/script.js'; ?></script> 
    
</body>

</html>

<?php
session_unset();
?>