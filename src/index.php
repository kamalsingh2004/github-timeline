<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $_SESSION['email'] = $email;

        $code = generateVerificationCode();
        $_SESSION['verification_code'] = $code;

        sendVerificationEmail($email, $code);
        $statusMessage = "Verification code sent to <strong>$email</strong>.";
    }

    if (isset($_POST['verification_code'])) {
        $enteredCode = trim($_POST['verification_code']);
        $sessionCode = $_SESSION['verification_code'] ?? '';
        $email = $_SESSION['email'] ?? '';

        if ($enteredCode === $sessionCode) {
            registerEmail($email);
            $statusMessage = "Email <strong>$email</strong> registered successfully!";
            unset($_SESSION['verification_code']);
        } else {
            $statusMessage = "<span style='color:red;'>Invalid verification code.</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Email Verification</title></head>
<body>
<h2>Register for GitHub Timeline</h2>

<?php if (!empty($statusMessage)): ?>
    <p><?= $statusMessage ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" required placeholder="Enter email">
    <button id="submit-email">Submit</button>
</form>

<br>

<form method="POST">
    <input type="text" name="verification_code" maxlength="6" required placeholder="Enter code">
    <button id="submit-verification">Verify</button>
</form>
</body>
</html>
