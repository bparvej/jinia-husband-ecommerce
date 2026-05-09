#!/bin/sh
set -e

echo "🏁 Starting entrypoint script..."
export NODE_ENV=${NODE_ENV:-production}

# Auto-detect production if running on Render
if [ "$RENDER" = "true" ]; then
  echo "🌐 Detected Render environment"
fi

# Try to detect connection info for up to 10 seconds (Render env injection can sometimes be slow)
for i in $(seq 1 10); do
  if [ -n "$DATABASE_URL" ] || [ -n "$DB_HOST" ]; then
    break
  fi
  echo "⏳ Waiting for environment variables (attempt $i/10)..."
  sleep 1
done

if [ -z "$DATABASE_URL" ] && [ -z "$DB_HOST" ] && [ "$RENDER" = "true" ]; then
    echo "************************************************************************"
    echo "❌ DEPLOYMENT STOPPED: DATABASE_URL IS MISSING"
    echo "Render is not providing the database connection string."
    echo "FIX: Go to Render Dashboard -> Web Service -> Environment -> Linked Databases"
    echo "And ensure your database is attached to this service."
    echo "************************************************************************"
    exit 1
fi

echo "⏳ Checking database availability..."
until pg_isready -d "${DATABASE_URL:-postgres://$DB_USER:$DB_PASSWORD@$DB_HOST:$DB_PORT/$DB_NAME}" -t 2; do
  echo "Waiting for database to be ready..."
  sleep 2
done
echo "✅ Database is reachable!"

echo "🔎 Step 0: Verifying migration files exist in /app/src/database/migrations..."
if [ ! -d "src/database/migrations" ]; then
    echo "❌ ERROR: No migration files found in src/database/migrations"
    exit 1
fi

echo "🛠️ Step 1: Skipping db:create (Using Managed Database)."
echo "Current Environment: $NODE_ENV"

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