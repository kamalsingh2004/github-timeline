#!/bin/bash

CRON_CMD="*/5 * * * * php $(pwd)/cron.php"
( crontab -l | grep -F "$CRON_CMD" ) >/dev/null 2>&1 || (
    (crontab -l 2>/dev/null; echo "$CRON_CMD") | crontab -
    echo "CRON entry added: $CRON_CMD"
)
