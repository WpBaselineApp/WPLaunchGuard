#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="${1:-$ROOT_DIR/.env.week0.local}"

if [[ ! -f "$ENV_FILE" ]]; then
  echo "Missing env file: $ENV_FILE"
  echo "Create it from .env.week0.example first."
  exit 1
fi

# shellcheck disable=SC1090
source "$ENV_FILE"

missing=0
warnings=0

need_cmd() {
  local cmd="$1"
  if ! command -v "$cmd" >/dev/null 2>&1; then
    echo "Missing command: $cmd"
    missing=1
  fi
}

warn_cmd() {
  local cmd="$1"
  local hint="$2"
  local user_bin="$HOME/bin/$cmd"
  if ! command -v "$cmd" >/dev/null 2>&1; then
    if [[ -x "$user_bin" ]]; then
      return 0
    fi
    echo "Warning: Missing optional command: $cmd ($hint)"
    warnings=1
  fi
}

need_var() {
  local name="$1"
  local value="${!name:-}"
  if [[ -z "$value" ]]; then
    echo "Missing env var: $name"
    missing=1
  fi
}

echo "Checking required local commands..."
need_cmd node
need_cmd npm
need_cmd git

echo "Checking optional local commands..."
warn_cmd wrangler "you can still proceed with Week 0; install before Cloudflare deploy tasks"
warn_cmd gh "you can still proceed with Week 0 when GITHUB_TOKEN is set"

echo "Checking required env vars..."
need_var PROJECT_SLUG
need_var CLOUDFLARE_ACCOUNT_ID
need_var CLOUDFLARE_API_TOKEN
need_var CF_D1_DB_NAME
need_var CF_R2_BUCKET
need_var CF_QUEUE_NAME
need_var GITHUB_ORG_OR_USER
need_var GITHUB_REPO
need_var GITHUB_TOKEN
need_var STRIPE_SECRET_KEY
need_var STRIPE_WEBHOOK_SECRET
need_var JWT_SIGNING_KEY
need_var ENCRYPTION_KEY

if [[ "$missing" -ne 0 ]]; then
  echo
  echo "Week 0 verification failed."
  exit 1
fi

echo
echo "Week 0 verification passed."
if [[ "$warnings" -ne 0 ]]; then
  echo "Week 0 verification passed with warnings."
fi
echo "Env file: $ENV_FILE"
