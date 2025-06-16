<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'functions.php';

$message = "";

// Step 1: Handle email form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // User submitted email
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $_SESSION['email'] = $email;

        $code = generateVerificationCode();
        $_SESSION['verification_code'] = $code;

        sendVerificationEmail($email, $code);
        $message = "Verification code sent to your email.";
    }

    // Step 2: Handle verification code submission
    if (isset($_POST['verification_code'])) {
        $userCode = trim($_POST['verification_code']);
        $actualCode = $_SESSION['verification_code'] ?? '';
        $email = $_SESSION['email'] ?? '';

        if ($userCode === $actualCode && !empty($email)) {
            registerEmail($email);
            $message = "Email verified and registered successfully!";
            unset($_SESSION['verification_code']); // Clean up
        } else {
            $message = "Invalid verification code. Try again.";
        }
    }
}
?>

<!-- HTML FORM SECTION -->
<h2>Email Verification</h2>
<p><?= $message ?></p>

<form method="post">
    <label for="email">Enter Email:</label><br>
    <input type="email" name="email" required><br>
    <button id="submit-email">Submit</button>
</form>

<br>

<form method="post">
    <label for="verification_code">Enter Verification Code:</label><br>
    <input type="text" name="verification_code" maxlength="6" required><br>
    <button id="submit-verification">Verify</button>
</form>
