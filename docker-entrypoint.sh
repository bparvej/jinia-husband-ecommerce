#!/bin/sh
set -e

echo "🏁 Starting entrypoint script..."

export NODE_ENV=${NODE_ENV:-production}

if [ "$RENDER" = "true" ]; then
  echo "🌐 Running on Render"
fi

# ------------------------------------------------------------------
# STEP 0: Fallback Construction
# ------------------------------------------------------------------
# If DATABASE_URL is missing but we have components (from render.yaml updates below)
if [ -z "$DATABASE_URL" ] && [ -n "$DB_HOST" ] && [ -n "$DB_USER" ] && [ -n "$DB_PASSWORD" ]; then
  echo "🔗 Building DATABASE_URL from individual components..."
  export DATABASE_URL="postgres://${DB_USER}:${DB_PASSWORD}@${DB_HOST}:${DB_PORT:-5432}/${DB_NAME}"
fi

# ------------------------------------------------------------------
# STEP 1: Validate DATABASE_URL
# ------------------------------------------------------------------

if [ -z "$DATABASE_URL" ] && [ "$RENDER" = "true" ]; then
  echo "************************************************************************"
  echo "❌ DATABASE_URL is missing."
  echo "DEBUG: Available ENV keys (names only):"
  env | cut -d= -f1 | grep -E "DB|DATABASE|RENDER" || echo "No DB env vars found."
  echo ""
  echo "ACTION REQUIRED:"
  echo "1. If using Blueprints: Check if 'homei-db' exists in your dashboard."
  echo "2. If manual: Go to 'homei-ecommerce' -> Environment -> Linked Databases"
  echo "   and click 'Add Database' to connect your Postgres instance."
  echo "************************************************************************"
  # Don't exit 1 yet; let pg_isready attempt a connection if components exist
  [ -z "$DATABASE_URL" ] && exit 1
fi

# ------------------------------------------------------------------
# STEP 2: Wait for PostgreSQL
# ------------------------------------------------------------------

if [ -n "$DATABASE_URL" ]; then

  echo "⏳ Waiting for PostgreSQL..."

  until pg_isready -d "$DATABASE_URL" -t 2; do
    echo "⌛ PostgreSQL not ready yet..."
    sleep 2
  done

  echo "✅ PostgreSQL is ready"

fi

# ------------------------------------------------------------------
# STEP 3: Run migrations
# ------------------------------------------------------------------

if [ -n "$DATABASE_URL" ]; then

  echo "🚀 Running migrations..."

  npx sequelize-cli db:migrate \
    --config src/config/config.js \
    --env "$NODE_ENV"

fi

# ------------------------------------------------------------------
# STEP 4: Optional Seeders
# ------------------------------------------------------------------

if [ "$RUN_SEEDERS" = "true" ]; then

  echo "🌱 Running seeders..."

  npx sequelize-cli db:seed:all \
    --config src/config/config.js \
    --env "$NODE_ENV"

fi

# ------------------------------------------------------------------
# STEP 5: Start App
# ------------------------------------------------------------------

echo "🟢 Starting Node server..."

exec "$@"