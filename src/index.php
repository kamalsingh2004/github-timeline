<!DOCTYPE html>
<html>
<head><title>Email Verification</title></head>
<body>

<h2>Register for GitHub Timeline</h2>
<form method="POST">
    <input type="email" name="email" required placeholder="Enter email">
    <button id="submit-email">Submit</button>
</form>

<br>

<form method="POST">
    <input type="text" name="verification_code" maxlength="6" required placeholder="Enter code">
    <button id="submit-verification">Verify</button>
</form>

<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Handle Email Submission
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $_SESSION['email'] = $email;

        $code = generateVerificationCode();
        $_SESSION['verification_code'] = $code;

        sendVerificationEmail($email, $code);
        echo "<p>Verification code sent to <strong>$email</strong>.</p>";
    }

    // Step 2: Handle Verification Code
    if (isset($_POST['verification_code'])) {
        $enteredCode = trim($_POST['verification_code']);
        $sessionCode = $_SESSION['verification_code'] ?? '';
        $email = $_SESSION['email'] ?? '';

        if ($enteredCode === $sessionCode) {
            registerEmail($email);
            echo "<p>Email <strong>$email</strong> registered successfully!</p>";
            unset($_SESSION['verification_code']);
        } else {
            echo "<p style='color:red;'>Invalid verification code.</p>";
        }
    }
}
?>

</body>
</html>

