#!/bin/bash
# Installs gd, intl and zip. Timestamps every step so the build log shows where time goes.
set -e
JOBS="${GAP_JOBS:-$(nproc)}"
T0=$(date +%s)
echo "gap-test: start $(date -u +%H:%M:%S) nproc=$(nproc) jobs=$JOBS mem=$(awk '/MemTotal/{print $2" kB"}' /proc/meminfo)"
if [ -r /sys/fs/cgroup/memory.max ]; then echo "gap-test: cgroup memory.max=$(cat /sys/fs/cgroup/memory.max)"; fi
apt-get update -y >/dev/null
apt-get install -y --no-install-recommends libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libicu-dev >/dev/null
T1=$(date +%s); echo "gap-test: apt done after $((T1-T0)) s"
docker-php-ext-configure gd --with-jpeg --with-freetype >/dev/null
docker-php-ext-install -j"$JOBS" gd >/dev/null
T2=$(date +%s); echo "gap-test: gd done after $((T2-T0)) s"
docker-php-ext-install -j"$JOBS" zip >/dev/null
T3=$(date +%s); echo "gap-test: zip done after $((T3-T0)) s"
docker-php-ext-install -j"$JOBS" intl >/dev/null
T4=$(date +%s); echo "gap-test: intl done after $((T4-T0)) s"
echo "jobs=$JOBS nproc=$(nproc) apt_s=$((T1-T0)) gd_s=$((T2-T1)) zip_s=$((T3-T2)) intl_s=$((T4-T3)) total_s=$((T4-T0)) finished=$(date -u +%Y-%m-%dT%H:%M:%SZ)" > /tmp/osc-setup-info
chmod 644 /tmp/osc-setup-info
php -m | grep -E '^(gd|intl|zip)$' | sed 's/^/gap-test: loaded /'
echo "gap-test: setup done in $((T4-T0)) s"
