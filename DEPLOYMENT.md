# Coolify Deployment Guide

## Issues Fixed

1. **Port Configuration**: Changed from port 8080 to 80 (Apache default)
2. **Apache Configuration**: Added proper PHP handling and security headers
3. **Environment Variables**: Updated docker-compose to use environment variables
4. **PHP Extensions**: Added opcache for better performance

## Coolify Setup Instructions

### 1. Environment Variables in Coolify

Set these environment variables in your Coolify project settings:

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password

JWT_SECRET=your-jwt-secret-key
JWT_TTL=1440

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 2. Generate APP_KEY

Run this command in your local project or in Coolify's terminal:

```bash
php artisan key:generate
```

### 3. Generate JWT Secret

Run this command:

```bash
php artisan jwt:secret
```

### 4. Database Migration

After deployment, run migrations:

```bash
php artisan migrate --force
```

### 5. Storage Permissions

Make sure storage and cache directories are writable:

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Common Issues and Solutions

### Bad Gateway Error

1. **Check Apache is running**: The container should be listening on port 80
2. **Check PHP-FPM**: Make sure PHP is properly configured
3. **Check logs**: View Apache error logs in Coolify
4. **Check database connection**: Verify DB credentials are correct

### Database Connection Issues

1. **Check DB_HOST**: Use the internal database hostname or IP
2. **Check firewall**: Ensure database port is accessible
3. **Test connection**: Use `php artisan tinker` to test DB connection

### Permission Issues

If you see permission errors:

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache vendor
```

## Testing the Deployment

1. **Health Check**: Visit `https://your-domain.com/up`
2. **API Test**: Try `GET https://your-domain.com/api/post`
3. **Check Logs**: Monitor Apache and Laravel logs in Coolify

## Notes

- The Dockerfile now uses port 80 (standard Apache port)
- Apache is configured to handle PHP files properly
- Environment variables are loaded from Coolify's environment settings
- Storage and cache directories have proper permissions set

