#!/bin/bash
# Maps http://goldmember.local and http://admin.goldmember.local to the Docker
# app container (ports 8080 / 8090) via the host's Apache. Idempotent.
# Usage: sudo bash docker/host-apache/setup-local-hosts.sh
set -e
DIR="$(cd "$(dirname "$0")" && pwd)"

for h in goldmember.local www.goldmember.local admin.goldmember.local; do
    grep -qE "^[^#]*\s$h(\s|$)" /etc/hosts || echo "127.0.0.1   $h" >> /etc/hosts
done

cp "$DIR/goldmember.local.conf" /etc/apache2/sites-available/goldmember.local.conf
a2enmod -q proxy proxy_http headers
a2ensite -q goldmember.local
apache2ctl configtest
systemctl reload apache2

echo "Done: http://goldmember.local  |  http://admin.goldmember.local"
