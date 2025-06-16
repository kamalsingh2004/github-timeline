#!/bin/bash

# Get current working directory
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Define CRON job
CRON_JOB="*/5 * * * * php $DIR/cron.php"

# Check if the job already exists
(crontab -l 2>/dev/null | grep -v "$DIR/cron.php" ; echo "$CRON_JOB") | crontab -

echo "✅ CRON job added successfully! It will run every 5 minutes."

