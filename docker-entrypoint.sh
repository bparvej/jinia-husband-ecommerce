#!/bin/sh
set -e

# Wait for database to be ready
# Defaults to host 'db' and port '5432' (Postgres) if variables aren't set
if [ -n "$DATABASE_URL" ]; then
  echo "⏳ Waiting for database connection via DATABASE_URL..."
  until pg_isready -d "$DATABASE_URL"; do
    sleep 1
  done
else
  echo "⚠️ DATABASE_URL not found. Skipping readiness check..."
fi
echo "✅ Database is reachable!"

echo "🔎 Step 0: Verifying migration files exist in /app/src/database/migrations..."
if [ ! -d "src/database/migrations" ] || [ -z "$(ls -A src/database/migrations)" ]; then
    echo "❌ ERROR: No migration files found in src/database/migrations"
    echo "Ensure your volumes and paths are correct."
    exit 1
fi
ls -1 src/database/migrations

echo "🛠️ Step 1: Ensuring database exists (${DB_NAME:-homei_db})..."
npx sequelize-cli db:create --config src/config/config.js --env ${NODE_ENV:-development} || echo "Database already exists."

echo "🚀 Step 2: Running database migrations..."
npx sequelize-cli db:migrate --config src/config/config.js --env ${NODE_ENV:-development}

# Validation: Check if the users table actually exists now
echo "🔎 Step 2.5: Validating 'users' table existence..."
if [ -n "$DATABASE_URL" ]; then
    TABLE_CHECK=$(psql "$DATABASE_URL" -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';")
else
    export PGPASSWORD=${DB_PASSWORD:-homei_secret_2026}
    TABLE_CHECK=$(psql -h ${DB_HOST:-db} -U ${DB_USER:-homei_user} -d ${DB_NAME:-homei_db} -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';")
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