#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
missing=0

check_file() {
  local file="$1"
  if [[ ! -f "$ROOT_DIR/$file" ]]; then
    echo "Missing file: $file"
    missing=1
  fi
}

echo "Checking Week 1 scaffold files..."
check_file "services/api-worker/src/index.js"
check_file "services/api-worker/wrangler.toml"
check_file "services/api-worker/migrations/0001_init.sql"
check_file "wordpress-plugin/wplaunchguard/wplaunchguard.php"
check_file "wordpress-plugin/wplaunchguard/includes/class-wplg-plugin.php"
check_file "wordpress-plugin/wplaunchguard/includes/class-wplg-admin.php"
check_file "scripts/week1-verify.sh"
check_file "docs/week1-bootstrap.md"
check_file ".github/workflows/cloudflare-deploy.yml"
check_file ".github/workflows/launchguard-scan.yml"

if [[ "$missing" -ne 0 ]]; then
  echo
  echo "Week 1 scaffold verification failed."
  exit 1
fi

echo

echo "Week 1 scaffold verification passed."
