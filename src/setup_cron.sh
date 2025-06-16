#!/bin/bash

# Absolute path to PHP
PHP_PATH=$(which php)

# Path to your cron.php file
CRON_FILE_PATH=$(realpath "$(dirname "$0")/cron.php")

# Add CRON entry
( crontab -l 2>/dev/null; echo "*/5 * * * * $PHP_PATH $CRON_FILE_PATH" ) | crontab -

echo "CRON job set to run every 5 minutes for: $CRON_FILE_PATH"

