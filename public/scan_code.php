<?php
declare(strict_types=1);

session_start();

require '../private/dbconnection.php';

if (!isset($_SESSION['register_email'])) {
    $_SESSION['login_error'] = "✖ Access Denied";
    header('Location: index.php');
    exit();
}

require '../vendor/autoload.php';
$link = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($_SESSION['register_name'], $_SESSION['secret'], 'Sunderland University');

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
                    <h3 style="margin-bottom: -2%;">Registered Successfully !!</h3>
                    <div class="qr-container">
                        <img src= "<?=$link;?>" alt="QR Code for Authenticator App" class="qr-code">
                    </div>
                    <p style="font-size: 12px;">Scan the QR code above with your Google or Microsoft Authenticator app to add your account automatically. Use <?php if (isset($_SESSION['secret'])) : ?><strong id="strong-text"><?= $_SESSION['secret'];?></strong><?php endif ?> to add account manually.</p>
                    <button class="submitbutton" id="submitButton" formaction="http://localhost:8080/uos2024/private/logout.php" style="margin: 10% 25% 10% 25%">Finish</button>
                </form>
            </div>
        </div>
    <script>
        <?php include 'script.js'; ?>
    </script>
</body>
</html>