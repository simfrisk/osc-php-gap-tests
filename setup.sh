#!/bin/bash
# Installs pdo_mysql only if the base image does not already have it, and records which case happened.
set -e
START=$(date +%s)
if php -m | grep -qi '^pdo_mysql$'; then
  echo "gap-test: pdo_mysql already in base image"
  BASE=yes
else
  echo "gap-test: pdo_mysql missing from base image, installing"
  BASE=no
  docker-php-ext-install pdo_mysql
fi
END=$(date +%s)
echo "pdo_mysql_in_base=$BASE setup_seconds=$((END-START)) setup_finished=$(date -u +%Y-%m-%dT%H:%M:%SZ)" > /tmp/osc-setup-info
chmod 644 /tmp/osc-setup-info
echo "gap-test: setup done in $((END-START)) s"
