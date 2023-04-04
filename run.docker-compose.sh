#!/bin/bash
source .env
export PROJECT_NAME

log_message()
{
    LOG_PREFIX="[$(date '+%Y-%m-%d %H:%M:%S')][rebuild]"
    MESSAGE=$1
    echo "$LOG_PREFIX $MESSAGE"
}


log_message "Stopping containers..."
docker compose -p trivia_api stop

log_message "Removing containers..."
docker compose -p trivia_api rm -f

log_message "Starting containers..."
docker compose -p trivia_api up -d --remove-orphans --build
