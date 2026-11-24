#!/usr/bin/env bash
set -euo pipefail

export PORT="${PORT:-8000}"

# Only PORT is substituted; nginx runtime variables such as $uri must survive.
envsubst '${PORT}' < /usr/local/etc/nginx/lara.conf.template > /home/serve/etc/nginx.conf

cd /Lara

# Keep the short delay from the old CMD for setups without a DB healthcheck.
sleep "${STARTUP_DELAY:-5}"
php artisan migrate --force

php-fpm --nodaemonize &
fpm_pid=$!

nginx -c /home/serve/etc/nginx.conf -g 'daemon off;' &
nginx_pid=$!

stop_children() {
  kill -TERM "$nginx_pid" "$fpm_pid" 2>/dev/null || true
  wait "$nginx_pid" 2>/dev/null || true
  wait "$fpm_pid" 2>/dev/null || true
}

trap stop_children TERM INT EXIT

# If either process exits, stop the other and let the container restart.
wait -n "$nginx_pid" "$fpm_pid"
