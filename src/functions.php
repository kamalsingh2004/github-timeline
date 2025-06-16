function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}
function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}
function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $updatedEmails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updatedEmails) . PHP_EOL);
}
function sendVerificationEmail($email, $code) {
    $subject = 'Your Verification Code';
    $message = "<p>Your verification code is: <strong>{$code}</strong></p>";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($email, $subject, $message, $headers);
}
