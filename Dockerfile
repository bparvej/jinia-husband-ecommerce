FROM node:20-alpine

WORKDIR /app

# Install system dependencies
RUN apk add --no-cache python3 make g++ postgresql-client

# Copy package files
COPY package*.json ./

# Install all dependencies
RUN npm install

# Copy app source
COPY . .

# Set up the entrypoint script
RUN chmod +x docker-entrypoint.sh

# Create uploads directory
RUN mkdir -p public/uploads

# Expose port
EXPOSE 3000

ENTRYPOINT ["sh", "./docker-entrypoint.sh"]
CMD ["node", "src/server.js"]
