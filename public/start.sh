#!/bin/bash

set -euo pipefail

cd "$( dirname "$0" )"
[ -f vendor/autoload.php ] || composer install
# LISTEN defaults to localhost for a host run; compose sets it to 0.0.0.0:8000
# so the container's published port is reachable.
php -S "${LISTEN:-localhost:8000}" index.php
