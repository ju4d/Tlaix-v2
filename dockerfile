# Dockerfile para Laravel + Python
FROM php:8.3-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    python3-minimal \
    python3-pip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && rm -rf /var/tmp/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite headers
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar composer files primero (para cache de Docker)
COPY composer.json composer.lock ./

# Copiar archivos del proyecto
COPY . .

# Crear .env desde .env.example si no existe
RUN if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || echo "APP_KEY=" > .env; fi

# Instalar dependencias de Python
RUN pip3 install --break-system-packages pandas numpy scikit-learn

# Instalar dependencias de Composer SIN ejecutar scripts post-install
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Crear directorios necesarios
RUN mkdir -p storage/app/predictions \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Crear archivo de historial vacío (se sobrescribirá si existe uno real)
RUN echo "date,demand" > storage/app/predictions/history.csv

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Crear symlink para python3
RUN ln -s /usr/bin/python3 /usr/bin/py

# Exponer puerto
EXPOSE 8080

# Script de inicio
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]