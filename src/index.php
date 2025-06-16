<form method="POST">
    <input type="email" name="email" required>
    <button type="submit" name="submit_email" id="submit-email">Submit</button>
</form>

<form method="POST">
    <input type="text" name="verification_code" maxlength="6" required>
    <button type="submit" name="submit_verification" id="submit-verification">Verify</button>
</form>

<?php
require_once 'functions.php';

if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];
    $code = generateVerificationCode();
    sendVerificationEmail($email, $code);
    file_put_contents(__DIR__ . "/codes/{$email}.txt", $code);
    echo "<p>Verification code sent to $email</p>";
}

if (isset($_POST['submit_verification'])) {
    $email = $_POST['email'];
    $user_code = $_POST['verification_code'];
    $stored_code = @file_get_contents(__DIR__ . "/codes/{$email}.txt");

    if ($user_code === $stored_code) {
        registerEmail($email);
        unlink(__DIR__ . "/codes/{$email}.txt");
        echo "<p>Email verified and registered.</p>";
    } else {
        echo "<p>Incorrect verification code.</p>";
    }
}
?>

