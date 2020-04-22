#!/usr/bin/env bash

set -e

echo "Caching configuration..."
cd /srv && php artisan config:cache && php artisan route:cache && php artisan view:cache

exec apache2-foreground
