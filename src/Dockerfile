# Dockerfile
FROM php:8.2-cli

# Copy your app
COPY . /app
WORKDIR /app

# Run PHP built-in server on Fly.io PORT
CMD php -S 0.0.0.0:${PORT:-8080} -t .
