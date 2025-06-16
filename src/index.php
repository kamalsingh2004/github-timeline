<?php
session_start();
require_once 'functions.php';

$message = '';

// === SUBSCRIPTION FORM PROCESSING ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SUBMIT EMAIL for subscription
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $_SESSION['email'] = $email;
        $_SESSION['verification_code'] = $code = generateVerificationCode();
        sendVerificationEmail($email, $code);
        $message = "Verification code sent to your email.";
    }

    // VERIFY CODE for subscription
    if (isset($_POST['verification_code'])) {
        $inputCode = trim($_POST['verification_code']);
        if (isset($_SESSION['verification_code']) && $inputCode === $_SESSION['verification_code']) {
            registerEmail($_SESSION['email']);
            $message = "Email verified and registered successfully!";
            unset($_SESSION['verification_code']);
        } else {
            $message = "Invalid verification code.";
        }
    }

    // SUBMIT EMAIL for unsubscription
    if (isset($_POST['unsubscribe_email'])) {
        $unsubscribeEmail = trim($_POST['unsubscribe_email']);
        $_SESSION['unsubscribe_email'] = $unsubscribeEmail;
        $_SESSION['unsubscribe_code'] = $code = generateVerificationCode();
        sendVerificationEmail($unsubscribeEmail, $code, true); // true = for unsubscribe
        $message = "Unsubscribe verification code sent.";
    }

    // VERIFY CODE for unsubscription
    if (isset($_POST['unsubscribe_verification_code'])) {
        $inputCode = trim($_POST['unsubscribe_verification_code']);
        if (isset($_SESSION['unsubscribe_code']) && $inputCode === $_SESSION['unsubscribe_code']) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            $message = "You have been unsubscribed successfully.";
            unset($_SESSION['unsubscribe_code']);
        } else {
            $message = "Invalid unsubscribe verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>GitHub Timeline Subscription</title>
</head>
<body>
    <h1>Subscribe to GitHub Timeline Updates</h1>
    <?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>

    <form method="post">
        <p>Enter your email:</p>
        <input type="email" name="email" required>
        <button type="submit" id="submit-email">Submit</button>
    </form>

    <form method="post">
        <p>Enter verification code:</p>
        <input type="text" name="verification_code" maxlength="6" required>
        <button type="submit" id="submit-verification">Verify</button>
    </form>

    <hr>

    <h1>Unsubscribe from GitHub Timeline Updates</h1>

    <form method="post">
        <p>Enter your email to unsubscribe:</p>
        <input type="email" name="unsubscribe_email" required>
        <button type="submit" id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <form method="post">
        <p>Enter unsubscribe verification code:</p>
        <input type="text" name="unsubscribe_verification_code" required>
        <button type="submit" id="verify-unsubscribe">Verify</button>
    </form>
</body>
</html>
