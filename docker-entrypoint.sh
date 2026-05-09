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
elif [ -n "$DB_HOST" ]; then
  echo "⏳ Waiting for database connection ($DB_HOST)..."
  until pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "${DB_USER:-postgres}" -t 30; do
    sleep 1
  done
  echo "✅ Database is reachable!"
elif [ "$RENDER" = "true" ] && [ -z "$DB_HOST" ]; then
  echo "************************************************************************"
  echo "❌ DEPLOYMENT STOPPED: DATABASE_URL IS MISSING"
  echo "ACTION REQUIRED: Go to Render Dashboard -> Web Service -> Environment"
  echo "Click 'Add Database' under 'Linked Databases' to connect your Postgres."
  echo "************************************************************************"
  exit 1
else
  echo "⚠️ Warning: No database connection info found (DATABASE_URL or DB_HOST). Startup might fail."
fi

echo "🔎 Step 0: Verifying migration files exist in /app/src/database/migrations..."
if [ ! -d "src/database/migrations" ] || [ -z "$(ls -A src/database/migrations)" ]; then
    echo "❌ ERROR: No migration files found in src/database/migrations"
    echo "Ensure your volumes and paths are correct."
    exit 1
fi
ls -1 src/database/migrations

echo "🛠️ Step 1: Skipping db:create (Using Managed Database)."

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
    TABLE_CHECK=$(psql "$DATABASE_URL" -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';" || echo "0")
elif [ -n "$DB_HOST" ] && command -v psql >/dev/null 2>&1; then
    export PGPASSWORD=${DB_PASSWORD}
    TABLE_CHECK=$(psql -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "$DB_USER" -d "$DB_NAME" -tAc "SELECT count(*) FROM information_schema.tables WHERE table_name = 'users';" || echo "0")
else
    echo "⚠️ Skipping table validation (missing connection info or psql client)."
    TABLE_CHECK="1"
fi

if [ "$TABLE_CHECK" = "0" ] && [ "$NODE_ENV" = "production" ]; then
    echo "❌ FATAL ERROR: Migrations completed but 'users' table is missing!"
    exit 1
fi

echo "🌱 Step 3: Running database seeders..."
npx sequelize-cli db:seed:all --config src/config/config.js --env "$NODE_ENV" || echo "💡 Seeding skipped (already seeded or failed)."

echo "🟢 Starting application..."
exec "$@"