#!/bin/sh
set -e

echo "🏁 Starting entrypoint script..."

export NODE_ENV=${NODE_ENV:-production}

if [ "$RENDER" = "true" ]; then
  echo "🌐 Running on Render"
fi

# ------------------------------------------------------------------
# STEP 1: Validate DATABASE_URL
# ------------------------------------------------------------------

MAX_WAIT=60
WAITED=0

while [ -z "$DATABASE_URL" ]; do
  echo "⏳ Waiting for DATABASE_URL..."

  sleep 2
  WAITED=$((WAITED + 2))

  if [ "$WAITED" -ge "$MAX_WAIT" ]; then
    echo "❌ DATABASE_URL was not injected after ${MAX_WAIT}s"
    echo "⚠️ Continuing startup anyway for debugging..."
    break
  fi
done

if [ -n "$DATABASE_URL" ]; then
  echo "✅ DATABASE_URL detected"
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