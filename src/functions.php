<?php

// Generate a 6-digit numeric verification code
function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Save verified email to registered_emails.txt (avoid duplicates)
function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

// Remove email from registered_emails.txt
function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    if (file_exists($file)) {
        $emails = file($file, FILE_IGNORE_NEW_LINES);
        $updated = array_filter($emails, fn($e) => trim($e) !== trim($email));
        file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
    }
}

// Simulated sending of verification email
function sendVerificationEmail($email, $code) {
    echo "<hr>";
    echo "<strong>--- Simulated Verification Email ---</strong><br>";
    echo "To: $email<br>";
    echo "Subject: Your Verification Code<br>";
    echo "<p>Your verification code is: <strong>$code</strong></p>";
    echo "Headers: From: no-reply@example.com | Content-Type: text/html";
    echo "<hr>";
}

// Simulated sending of unsubscribe confirmation email
function sendUnsubscribeVerification($email, $code) {
    echo "<hr>";
    echo "<strong>--- Simulated Unsubscribe Email ---</strong><br>";
    echo "To: $email<br>";
    echo "Subject: Confirm Unsubscription<br>";
    echo "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
    echo "Headers: From: no-reply@example.com | Content-Type: text/html";
    echo "<hr>";
}

// Fetch GitHub timeline data (simulated)
function fetchGitHubTimeline() {
    // Simulating as GitHub doesn't allow fetching timeline directly
    return [
        ['event' => 'Push', 'user' => 'testuser'],
        ['event' => 'Fork', 'user' => 'john_doe'],
    ];
}

// Convert fetched GitHub data into formatted HTML
function formatGitHubData($data) {
    $html = "<h2>GitHub Timeline Updates</h2>";
    $html .= "<table border='1'><tr><th>Event</th><th>User</th></tr>";
    foreach ($data as $entry) {
        $html .= "<tr><td>{$entry['event']}</td><td>{$entry['user']}</td></tr>";
    }
    $html .= "</table>";
    return $html;
}

// Simulated sending of GitHub timeline update email to all users
function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $data = fetchGitHubTimeline();
    $html = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribeLink = "http://localhost/github-timeline/src/unsubscribe.php?email=" . urlencode($email);
        $body = $html . "<p><a href='$unsubscribeLink' id='unsubscribe-button'>Unsubscribe</a></p>";

        echo "<hr>";
        echo "<strong>--- Simulated GitHub Update Email ---</strong><br>";
        echo "To: $email<br>";
        echo "Subject: Latest GitHub Updates<br>";
        echo $body;
        echo "<br>Headers: From: no-reply@example.com | Content-Type: text/html";
        echo "<hr>";
    }
}

?>
