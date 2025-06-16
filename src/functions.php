echo "functions.php loaded\n";
function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}
function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    
    // Avoid duplicate entries
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}
function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';

    if (file_exists($file)) {
        $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updated = array_filter($emails, function ($e) use ($email) {
            return trim($e) !== trim($email);
        });

        file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
    }
}
function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";

    mail($email, $subject, $message, $headers);
}
function fetchGitHubTimeline() {
    // Simulate GitHub timeline data (real API is not available)
    // In real case: $data = file_get_contents('https://www.github.com/timeline');
    return [
        ['event' => 'Push', 'user' => 'testuser'],
        ['event' => 'Fork', 'user' => 'dev123']
    ];
}
function formatGitHubData($data) {
    $html = "<h2>GitHub Timeline Updates</h2>";
    $html .= "<table border='1'>";
    $html .= "<tr><th>Event</th><th>User</th></tr>";

    foreach ($data as $item) {
        $html .= "<tr><td>{$item['event']}</td><td>{$item['user']}</td></tr>";
    }

    $html .= "</table>";
    $html .= "<p><a href='http://yourdomain.com/src/unsubscribe.php' id='unsubscribe-button'>Unsubscribe</a></p>";

    return $html;
}
function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $data = fetchGitHubTimeline();
    $htmlContent = formatGitHubData($data);

    $subject = "Latest GitHub Updates";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";

    foreach ($emails as $email) {
        mail(trim($email), $subject, $htmlContent, $headers);
    }
}
