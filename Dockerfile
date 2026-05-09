FROM node:20-alpine

WORKDIR /app

RUN apk add --no-cache \
    python3 \
    make \
    g++ \
    postgresql-client \
    dos2unix

COPY package*.json .

RUN npm ci --omit=dev

COPY . .

RUN mkdir -p public/uploads/products
RUN chmod -R 755 public/uploads

RUN dos2unix docker-entrypoint.sh && \
    chmod +x docker-entrypoint.sh

ENV NODE_ENV=production

EXPOSE 10000

ENTRYPOINT ["sh", "./docker-entrypoint.sh"]

CMD ["node", "src/server.js"]