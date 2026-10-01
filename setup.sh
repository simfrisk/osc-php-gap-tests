#!/bin/bash
# Raise upload limits and fix error output at the PHP level, by writing a conf.d ini file at build time.
set -e
cat > /usr/local/etc/php/conf.d/zz-gap-uploads.ini <<'INI'
upload_max_filesize = 20M
post_max_size = 25M
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /dev/stderr
INI
echo "conf_d_written=$(date -u +%Y-%m-%dT%H:%M:%SZ)" > /tmp/osc-setup-info
chmod 644 /tmp/osc-setup-info
echo "gap-test: upload ini written"
