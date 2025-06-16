<?php

function generateVerificationCode() {
    return rand(100000, 999999);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "From: no-reply@example.com\r\nContent-type: text/html\r\n";
    mail($email, $subject, $message, $headers);
}

function fetchGitHubTimeline() {
    return file_get_contents("https://www.github.com/timeline");
}

function formatGitHubData($data) {
    // This is just a sample, real parsing will depend on actual GitHub timeline response
    return "<h2>GitHub Timeline Updates</h2><table border='1'><tr><th>Event</th><th>User</th></tr><tr><td>Push</td><td>testuser</td></tr></table>";
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $data = fetchGitHubTimeline();
    $html = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribe_link = "http://yourdomain.com/unsubscribe.php?email=" . urlencode($email);
        $message = $html . "<p><a href=\"$unsubscribe_link\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
        $headers = "From: no-reply@example.com\r\nContent-type: text/html\r\n";
        mail($email, "Latest GitHub Updates", $message, $headers);
    }
}
?>

