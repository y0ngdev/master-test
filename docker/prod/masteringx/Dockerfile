FROM php:8.3-fpm AS base

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends --fix-missing \
      software-properties-common \
      apt-utils \
      libpq-dev \
      libpng-dev \
      openssl \
      libjpeg-dev \
      libfreetype6-dev \
      libwebp-dev \
      libxpm-dev \
      libgd-dev \
      libzip-dev \
      libicu-dev \
      zip unzip git \
      libonig-dev \
      libxml2-dev \
      libsqlite3-dev \
      libssl-dev \
      curl \
      libcurl4-openssl-dev \
      ffmpeg \
      && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
      && docker-php-ext-install gd \
      && docker-php-ext-enable gd \
      && docker-php-ext-install intl mbstring pdo_mysql mysqli bcmath pcntl zip opcache fileinfo soap \
      && docker-php-ext-enable pdo_mysql mysqli intl mbstring pcntl zip opcache fileinfo soap\
      && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html
########################################################
# Stage 1: Builder
########################################################
FROM base AS builder

USER root

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends  nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*



# Copy the rest of the application source (PHP, Vue components, assets, etc.)
COPY --chown=www-data:www-data . .

# Copy in JS dependency manifests, install front-end deps


RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

RUN npm ci

# Build your Vite/Inertia assets
USER www-data

RUN npm run build

WORKDIR /var/www/html

########################################################
# Stage 2: Production
########################################################
FROM base AS production

# libfcgi-bin and procps are required for the php-fpm-healthcheck script
RUN apt-get update && apt-get install -y --no-install-recommends \
    libfcgi-bin procps \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Healthcheck script
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck


# Enable PHP-FPM status page by modifying zz-docker.conf with sed
RUN sed -i '/\[www\]/a pm.status_path = /status' /usr/local/etc/php-fpm.d/zz-docker.conf
# Update the variables_order to include E (for ENV)
#RUN sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini"


RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY ./docker/prod/masteringx/zzz-custom-php.ini /usr/local/etc/php/conf.d/

COPY ./docker/prod/masteringx/opcache.ini /usr/local/etc/php/conf.d/opcache.ini


# Copy the initialization script
COPY ./docker/prod/masteringx/entrypoint.d/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY ./docker/prod/masteringx/entrypoint.d/laravel-automations.sh /usr/local/bin/laravel-automations.sh
RUN chmod +x /usr/local/bin/laravel-automations.sh


COPY ./docker/prod/masteringx/entrypoint.d/test-db.sh /usr/local/bin/test-db.sh
RUN chmod +x /usr/local/bin/test-db.sh

# Copy the application code and dependencies from the build stage
COPY --chown=www-data:www-data --from=builder /var/www/html /var/www/html

RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage


# Set working directory
WORKDIR /var/www/html


USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Change the default command to run the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
