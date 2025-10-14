FROM php:8.3-apache

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

RUN a2enmod rewrite headers

RUN mkdir -p \
    /var/www/html/storage/app/predictions \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN pip3 install --no-cache-dir --break-system-packages pandas numpy scikit-learn \
    && rm -rf ~/.cache/pip/*

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && rm -rf /root/.composer/cache/*

COPY . .

RUN if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || echo "APP_KEY=" > .env; fi \
    && echo "date,demand" > storage/app/predictions/history.csv \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && ln -s /usr/bin/python3 /usr/bin/py

COPY mysql-cert.pem /tmp/mysql-cert.pem

EXPOSE 8080

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
