#!/bin/bash
# Runs once on the very first `docker compose up` (when the data volume is
# empty). Docker's MariaDB entrypoint executes *.sh files from
# /docker-entrypoint-initdb.d/ after creating the initial database/user
# defined by MYSQL_DATABASE / MYSQL_USER / MYSQL_PASSWORD environment vars.
set -e

echo ">>> [goldmember] Importing SQL dump into gssamru_goldmember ..."

mysql \
    -u root \
    -p"${MYSQL_ROOT_PASSWORD}" \
    gssamru_goldmember \
    < /docker-entrypoint-initdb.d/gssamru_goldmember.sql

echo ">>> [goldmember] Database import completed successfully."


