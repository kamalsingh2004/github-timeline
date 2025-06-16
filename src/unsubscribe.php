<?php
require_once 'functions.php';

if (!file_exists(__DIR__ . '/codes')) {
    mkdir(__DIR__ . '/codes');
}
?>

<!-- Unsubscribe Form -->
<form method="POST">
    <input type="email" name="unsubscribe_email" required>
    <button type="submit" name="submit_unsubscribe" id="submit-unsubscribe">Unsubscribe</button>
</form>

<!-- Unsubscribe Verification -->
<form method="POST">
    <input type="email" name="unsubscribe_email" required>
    <input type="text" name="unsubscribe_verification_code" required>
    <button type="submit" name="verify_unsubscribe" id="verify-unsubscribe">Verify</button>
</form>

<?php
if (isset($_POST['submit_unsubscribe'])) {
    $email = $_POST['unsubscribe_email'];
    $code = generateVerificationCode();
    sendVerificationEmail($email, $code);  // reuse same function
    file_put_contents(__DIR__ . "/codes/unsub_$email.txt", $code);
    echo "<p>Unsubscribe verification code sent to $email</p>";
}

if (isset($_POST['verify_unsubscribe'])) {
    $email = $_POST['unsubscribe_email'];
    $user_code = $_POST['unsubscribe_verification_code'];
    $stored_code = @file_get_contents(__DIR__ . "/codes/unsub_$email.txt");

    if ($user_code === $stored_code) {
        unsubscribeEmail($email);
        unlink(__DIR__ . "/codes/unsub_$email.txt");
        echo "<p>Email successfully unsubscribed.</p>";
    } else {
        echo "<p>Incorrect verification code.</p>";
    }
}
?>

