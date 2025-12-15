# استخدم صورة PHP مع Apache
FROM php:8.4-apache

# إعداد مجلد public كجذر للموقع
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# تعديل إعدادات Apache لتوجيه للـ public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# تثبيت المتطلبات اللازمة لـ Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring exif pcntl bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تعيين مجلد العمل
WORKDIR /var/www/html

# نسخ ملفات Laravel
COPY . .

# تفعيل mod_rewrite في Apache
RUN a2enmod rewrite

# إنشاء مجلدات التخزين والكاش مع الصلاحيات الصحيحة
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# تثبيت الحزم بـ Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev || \
    (echo "Composer install failed, trying without --no-dev" && \
    composer install --no-interaction --prefer-dist --optimize-autoloader)

# تغيير صلاحيات الملفات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# فتح البورت
EXPOSE 80