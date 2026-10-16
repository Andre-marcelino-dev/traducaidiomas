# docker/php/Dockerfile 
FROM php:8.3-fpm 
 
RUN apt-get update && apt-get install -y \ 
    git \ 
    curl \ 
    unzip \ 
    zip \ 
    libpng-dev \ 
    libjpeg62-turbo-dev \ 
    libfreetype6-dev \ 
    libonig-dev \ 
    libxml2-dev \ 
    libzip-dev \ 
    libicu-dev \ 
    default-mysql-client \ 
    && docker-php-ext-configure gd --with-freetype --with-jpeg \ 
    && docker-php-ext-install \ 
        pdo \ 
        pdo_mysql \ 
        mbstring \ 
        exif \ 
        pcntl \ 
        bcmath \ 
        gd \ 
        zip \ 
        intl \ 
    && apt-get clean \ 
    && rm -rf /var/lib/apt/lists/* 
 
RUN curl -sS https://getcomposer.org/installer | php -- \ 
    --install-dir=/usr/local/bin \ 
    --filename=composer 
 
WORKDIR /var/www/html

# Diretório usado pelo compilador de views em ambiente Docker.
RUN mkdir -p /tmp/traduca-framework/views \
    && chmod -R 777 /tmp/traduca-framework

# O PHP-FPM executa como www-data e precisa compilar views e caches.
RUN mkdir -p /var/www/html/storage/framework/{cache,sessions,views} /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
 
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
CMD ["php-fpm"] 
 
# Instala extensões PHP necessárias para o Laravel 
RUN docker-php-ext-install \ 
    pdo_mysql mbstring exif pcntl bcmath gd zip 
 
# Instala o Composer 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 
 
WORKDIR /var/www/html 
# Cria pastas de upload e define permissões
RUN mkdir -p /var/www/html/public/traducaidiomas/professor \
    && mkdir -p /var/www/html/public/traducaidiomas/alunos \
    && mkdir -p /var/www/html/public/traducaidiomas/materiais \
    && mkdir -p /var/www/html/public/traducaidiomas/servicos \
    && mkdir -p /var/www/html/public/traducaidiomas/img \
    && mkdir -p /var/www/html/public/traducaidiomas/banners \
    && chmod -R 777 /var/www/html/public/traducaidiomas
