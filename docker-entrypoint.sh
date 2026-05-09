#!/bin/sh
set -e

echo "🏁 Starting entrypoint script..."

# Auto-detect production if running on Render
if [ "$RENDER" = "true" ]; then
  echo "🌐 Detected Render environment"
  export NODE_ENV=production
fi

if [ -n "$DATABASE_URL" ]; then
  echo "⏳ Waiting for database connection..."
  # Use pg_isready to check database availability. Timeout after 30 seconds.
  until pg_isready -d "$DATABASE_URL" -t 30; do
    sleep 1
  done
  echo "✅ Database is reachable!"
else
  echo "❌ ERROR: DATABASE_URL not found."
  echo "Please link a Render PostgreSQL database to this service in the Render Dashboard."
  exit 1
fi

echo "🔎 Step 0: Verifying migration files exist in /app/src/database/migrations..."
if [ ! -d "src/database/migrations" ] || [ -z "$(ls -A src/database/migrations)" ]; then
    echo "❌ ERROR: No migration files found in src/database/migrations"
    echo "Ensure your volumes and paths are correct."
    exit 1
fi
ls -1 src/database/migrations

if [ -n "$DB_HOST" ] && [ -z "$DATABASE_URL" ]; then
    echo "🛠️ Step 1: Ensuring database exists (using DB_HOST directly)..."
    npx sequelize-cli db:create --config src/config/config.js --env "$NODE_ENV" || echo "💡 Database already exists (likely in development)."
else
    echo "🛠️ Step 1: Skipping db:create (Using Managed Database via DATABASE_URL)."
fi

echo "🚀 Step 2: Running database migrations..."
npx sequelize-cli db:migrate --config src/config/config.js --env "$NODE_ENV" || exit 1

# After migrations, ensure the connection is still valid before proceeding.
if [ -n "$DATABASE_URL" ]; then
  echo "✅ Database connection re-validated after migrations."
  pg_isready -d "$DATABASE_URL" || (echo "❌ Database connection lost after migrations!" && exit 1)
fi

# Validation: Check if the users table actually exists now
echo "🔎 Step 2.5: Validating 'users' table existence..."
if [ -n "$DATABASE_URL" ] && command -v psql >/dev/null 2>&1; then
    TABLE_CHECK=$(psql "$DATABASE_URL" -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';")
elif [ -n "$DB_HOST" ] && which psql >/dev/null 2>&1; then
    export PGPASSWORD=${DB_PASSWORD:-homei_secret_2026}
    TABLE_CHECK=$(psql -h ${DB_HOST:-db} -U ${DB_USER:-homei_user} -d ${DB_NAME:-homei_db} -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';")
else
    echo "⚠️ Skipping table validation (missing connection info or psql client)."
    TABLE_CHECK="1"
fi

if [ "$TABLE_CHECK" = "0" ]; then
    echo "❌ FATAL ERROR: Migrations completed but 'users' table is missing!"
    exit 1
fi

echo "🌱 Step 3: Running database seeders..."
npx sequelize-cli db:seed:all --config src/config/config.js --env "$NODE_ENV" || echo "💡 Seeding skipped (already seeded or failed)."

echo "🟢 Starting application..."
exec "$@"