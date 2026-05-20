FROM php:8.2-cli

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel y SQLite
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo dentro del contenedor
WORKDIR /app

# Copiar los archivos del proyecto
COPY . .

# Instalar las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Configurar permisos para SQLite y almacenamiento
RUN mkdir -p database && touch database/database.sqlite && chmod -R 777 database storage

# Comando de arranque: ejecuta migraciones, semillas y enciende el servidor en el puerto de Render
CMD php artisan migrate:fresh --seed --force && php artisan serve --host 0.0.0.0 --port $PORT
