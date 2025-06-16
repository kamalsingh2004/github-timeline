<?php
session_start();
require_once 'functions.php';

// Enable errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: User entered email to unsubscribe
    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        $_SESSION['unsubscribe_email'] = $email;

        $code = generateVerificationCode();
        $_SESSION['unsubscribe_code'] = $code;

        sendUnsubscribeVerification($email, $code);
        $message = "Unsubscribe verification code sent to your email.";
    }

    // Step 2: User entered verification code
    if (isset($_POST['unsubscribe_verification_code'])) {
        $userCode = trim($_POST['unsubscribe_verification_code']);
        $sessionCode = $_SESSION['unsubscribe_code'] ?? '';
        $email = $_SESSION['unsubscribe_email'] ?? '';

        if ($userCode === $sessionCode && $email) {
            unsubscribeEmail($email);
            $message = "You have been unsubscribed successfully.";
            unset($_SESSION['unsubscribe_code']);
        } else {
            $message = "Incorrect code. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
</head>
<body>
    <h2>Unsubscribe from GitHub Timeline Updates</h2>
    <?php if ($message): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <!-- Unsubscribe Email Form -->
    <form method="post">
        <label>Enter your email to unsubscribe:</label><br>
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <br>

    <!-- Unsubscribe Code Form -->
    <form method="post">
        <label>Enter unsubscribe verification code:</label><br>
        <input type="text" name="unsubscribe_verification_code" maxlength="6" required>
        <button id="verify-unsubscribe">Verify</button>
    </form>
</body>
</html>
