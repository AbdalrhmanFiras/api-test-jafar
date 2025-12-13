# استخدم صورة PHP مع Apache
FROM php:8.2-apache

# إعداد مجلد public كجذر للموقع
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# تعديل إعدادات Apache لتوجيه للـ public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# تثبيت المتطلبات اللازمة لـ Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring exif pcntl bcmath
# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات Laravel
COPY . /var/www/html

# تفعيل mod_rewrite في Apache
RUN a2enmod rewrite

# تغيير صلاحيات الملفات
RUN chown -R www-data:www-data /var/www/html

# تثبيت الحزم بـ Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader


# فتح البورت
EXPOSE 80