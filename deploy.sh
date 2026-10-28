#!/usr/bin/env bash
#
# Deploy-Script für medium-clone auf Hostinger.
# Auf dem Server im Projektordner ausführen:  bash deploy.sh
#
set -euo pipefail

echo "==> In den Maintenance-Modus schalten"
php artisan down || true

echo "==> Neuesten Stand holen"
git pull --ff-only

echo "==> Composer-Abhängigkeiten (ohne dev)"
composer install --no-dev --optimize-autoloader

echo "==> Frontend-Assets bauen"
npm ci
npm run build

echo "==> Datenbank-Migrationen"
php artisan migrate --force

echo "==> Caches neu aufbauen"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Maintenance-Modus beenden"
php artisan up

echo "==> Fertig."
