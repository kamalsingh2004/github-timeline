<?php
require_once 'functions.php';

// Enable errors (helpful for CRON job debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Send GitHub timeline updates to all registered users
sendGitHubUpdatesToSubscribers();
