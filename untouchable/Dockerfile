FROM php:8.2-cli

WORKDIR /app
COPY . .

# Render injecte la variable PORT automatiquement
CMD php -S 0.0.0.0:${PORT:-10000}
