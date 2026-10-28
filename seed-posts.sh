#!/usr/bin/env bash
#
# Schreibt 100 Posts (user_id = 1) direkt in die MySQL-Datenbank.
# DB-Zugangsdaten werden aus der .env im Projektordner gelesen.
#
# Auf dem Server im Laravel-Projektordner ausführen:
#   bash seed-posts.sh            # schreibt in die DB
#   bash seed-posts.sh --print    # zeigt nur das SQL, schreibt nichts
#
set -euo pipefail

cd "$(dirname "$0")"

USER_ID=1
COUNT=100

PRINT_ONLY=false
[ "${1:-}" = "--print" ] && PRINT_ONLY=true

# --- Wortlisten für abwechslungsreiche Titel ---
adjectives=( "The Complete" "A Practical" "Rethinking" "Understanding" "Inside" \
             "The Future of" "Mastering" "A Beginner Guide to" "Lessons From" "Why" )
topics=( "Modern Web Development" "Healthy Habits" "Space Exploration" \
         "Endurance Training" "Public Policy" "Independent Film" "Clean Code" \
         "Mindful Living" "Renewable Energy" "Data Privacy" "Remote Work" \
         "Artificial Intelligence" "Urban Gardening" "Open Source" )
sentences=( \
  "This article explores the topic in depth and shares practical takeaways." \
  "We look at common pitfalls and how to avoid them in everyday work." \
  "The ideas here are drawn from real projects and hard-won experience." \
  "Each section builds on the last, so you can follow along step by step." \
  "By the end you should feel confident applying these principles yourself." \
  "There is no single right answer, but a few patterns consistently work well." \
  "Small, deliberate improvements add up to meaningful results over time." \
  "We close with a short checklist you can keep nearby for reference." )

# --- SQL zusammenbauen ---
build_sql() {
  cat <<'SQL'
INSERT IGNORE INTO categories (id, name, created_at, updated_at) VALUES
 (1,'Technology',NOW(),NOW()),
 (2,'Health',NOW(),NOW()),
 (3,'Science',NOW(),NOW()),
 (4,'Sports',NOW(),NOW()),
 (5,'Politics',NOW(),NOW()),
 (6,'Entertainment',NOW(),NOW());
SQL

  echo "INSERT IGNORE INTO posts (image,title,slug,content,category_id,user_id,published_at,created_at,updated_at) VALUES"

  local i rows=""
  for ((i = 1; i <= COUNT; i++)); do
    local adj="${adjectives[$(((i - 1) % ${#adjectives[@]}))]}"
    local top="${topics[$(((i - 1) % ${#topics[@]}))]}"
    local title="$adj $top"

    local slug
    slug="$(echo "$title" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')-$i"

    local content="${sentences[$((i % ${#sentences[@]}))]} ${sentences[$(((i + 1) % ${#sentences[@]}))]} ${sentences[$(((i + 2) % ${#sentences[@]}))]}"

    local category_id=$(((i - 1) % 6 + 1))
    local image="https://picsum.photos/seed/post${i}/640/480"

    # ~20% als Entwurf (NULL), sonst gestaffeltes Veröffentlichungsdatum
    local published="DATE_SUB(NOW(), INTERVAL $i DAY)"
    if ((i % 5 == 0)); then
      published="NULL"
    fi

    local sep=","
    if ((i == COUNT)); then
      sep=";"
    fi

    rows+=" ('$image','$title','$slug','$content',$category_id,$USER_ID,$published,NOW(),NOW())$sep"$'\n'
  done
  printf '%s' "$rows"
}

SQL_TEXT="$(build_sql)"

if $PRINT_ONLY; then
  printf '%s\n' "$SQL_TEXT"
  exit 0
fi

# --- DB-Konfiguration aus .env lesen (nur wenn wirklich geschrieben wird) ---
get_env() { grep -E "^$1=" .env 2>/dev/null | head -1 | cut -d= -f2- | tr -d '"' | tr -d "'" || true; }
DB_HOST="$(get_env DB_HOST)";     DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="$(get_env DB_PORT)";     DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="$(get_env DB_DATABASE)"
DB_USERNAME="$(get_env DB_USERNAME)"
DB_PASSWORD="$(get_env DB_PASSWORD)"

if [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ]; then
  echo "Abbruch: DB_DATABASE/DB_USERNAME nicht in .env gefunden." >&2
  exit 1
fi

mysql_run() {
  mysql --host="$DB_HOST" --port="$DB_PORT" --user="$DB_USERNAME" \
        --password="$DB_PASSWORD" "$DB_DATABASE" "$@"
}

# --- prüfen, dass der User existiert (Fremdschlüssel) ---
exists="$(mysql_run -N -e "SELECT COUNT(*) FROM users WHERE id=$USER_ID;")"
if [ "$exists" -eq 0 ]; then
  echo "Abbruch: Kein User mit id=$USER_ID vorhanden. Bitte zuerst registrieren." >&2
  exit 1
fi

printf '%s\n' "$SQL_TEXT" | mysql_run
echo "Fertig: $COUNT Posts (user_id=$USER_ID) geschrieben."
