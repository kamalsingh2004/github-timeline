<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'functions.php';

$message = "";

// Step 1: Handle unsubscribe email submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        $_SESSION['unsubscribe_email'] = $email;

        $code = generateVerificationCode();
        $_SESSION['unsubscribe_verification_code'] = $code;

        // Custom unsubscribe email body
        $subject = "Confirm Unsubscription";
        $body = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";

        mail($email, $subject, $body, $headers);
        $message = "A confirmation code was sent to your email.";
    }

    // Step 2: Handle unsubscribe verification
    if (isset($_POST['unsubscribe_verification_code'])) {
        $userCode = trim($_POST['unsubscribe_verification_code']);
        $actualCode = $_SESSION['unsubscribe_verification_code'] ?? '';
        $email = $_SESSION['unsubscribe_email'] ?? '';

        if ($userCode === $actualCode && !empty($email)) {
            unsubscribeEmail($email);
            $message = "You have been unsubscribed successfully.";
            unset($_SESSION['unsubscribe_verification_code']);
        } else {
            $message = "Invalid verification code. Try again.";
        }
    }
}
?>

<!-- HTML FORM SECTION -->
<h2>Unsubscribe</h2>
<p><?= $message ?></p>

<form method="post">
    <label for="unsubscribe_email">Enter your email to unsubscribe:</label><br>
    <input type="email" name="unsubscribe_email" required><br>
    <button id="submit-unsubscribe">Unsubscribe</button>
</form>

<br>

<form method="post">
    <label for="unsubscribe_verification_code">Enter Unsubscribe Code:</label><br>
    <input type="text" name="unsubscribe_verification_code" required><br>
    <button id="verify-unsubscribe">Verify</button>
</form>

