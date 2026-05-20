FROM node:20-alpine

WORKDIR /app

# Install system dependencies needed for some native node modules
RUN apk add --no-cache python3 make g++

# Copy dependency manifests
COPY package*.json ./

# Install ONLY production dependencies to keep the image fast and secure
RUN npm ci --only=production

# Copy the rest of your application code
COPY . .

# Ensure the public uploads directory exists
RUN mkdir -p public/uploads

EXPOSE 3000

# Run migrations first, then start the production server
CMD ["sh", "-c", "npm run migrate && npm start"]