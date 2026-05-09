#!/bin/sh
set -e

# Auto-detect production if running on Render
if [ "$RENDER" = "true" ] && [ -z "$NODE_ENV" ]; then
  export NODE_ENV=production
fi

if [ -n "$DATABASE_URL" ]; then
  echo "⏳ Waiting for database connection via DATABASE_URL..."
  until pg_isready -d "$DATABASE_URL"; do
    sleep 1
  done
  echo "✅ Database is reachable!"
elif [ -n "$DB_HOST" ]; then
  echo "⏳ Waiting for database (${DB_HOST}:${DB_PORT:-5432})..."
  until pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}"; do
    sleep 1
  done
  echo "✅ Database is reachable!"
else
  echo "⚠️ Warning: No database connection info found (DATABASE_URL or DB_HOST)."
  if [ "$NODE_ENV" = "production" ]; then
    echo "❌ Error: Database connection string is required in production."
    exit 1
  fi
fi

echo "🔎 Step 0: Verifying migration files exist in /app/src/database/migrations..."
if [ ! -d "src/database/migrations" ] || [ -z "$(ls -A src/database/migrations)" ]; then
    echo "❌ ERROR: No migration files found in src/database/migrations"
    echo "Ensure your volumes and paths are correct."
    exit 1
fi
ls -1 src/database/migrations

echo "🛠️ Step 1: Ensuring database exists (${DB_NAME:-homei_db})..."
if [ -z "$DATABASE_URL" ]; then
    npx sequelize-cli db:create --config src/config/config.js --env ${NODE_ENV:-development} || echo "Database already exists or cannot be created automatically."
else
    echo "Using existing database provided via DATABASE_URL..."
fi

echo "🚀 Step 2: Running database migrations..."
npx sequelize-cli db:migrate --config src/config/config.js --env ${NODE_ENV:-development}

# Validation: Check if the users table actually exists now
echo "🔎 Step 2.5: Validating 'users' table existence..."
if [ -n "$DATABASE_URL" ] && command -v psql >/dev/null 2>&1; then
    TABLE_CHECK=$(psql "$DATABASE_URL" -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';")
elif [ -n "$DB_HOST" ] && command -v psql >/dev/null 2>&1; then
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
# We use '|| true' so that if seeds have already been run (causing unique constraint errors),
# the container doesn't crash and the app still starts.
npx sequelize-cli db:seed:all --config src/config/config.js --env ${NODE_ENV:-development} || echo "⚠️ Seeding skipped (likely already seeded)."

echo "🟢 Starting application..."
exec "$@"