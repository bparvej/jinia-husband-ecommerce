FROM node:20-alpine

WORKDIR /app

# Install required packages
RUN apk add --no-cache python3 make g++ postgresql-client

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --omit=dev

# Copy source code
COPY . .

# Create uploads directory
RUN mkdir -p public/uploads/products

# Make entrypoint executable
RUN chmod +x docker-entrypoint.sh

# Render injects PORT dynamically
ENV PORT=10000

# Expose Render port
EXPOSE 10000

ENTRYPOINT ["sh", "./docker-entrypoint.sh"]

CMD ["node", "src/server.js"]