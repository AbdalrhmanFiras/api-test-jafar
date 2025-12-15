# Troubleshooting Internal Server Error

## Quick Fixes

### 1. Check Laravel Logs
```bash
# In Coolify terminal or container
tail -f storage/logs/laravel.log
```

### 2. Common Issues and Solutions

#### Missing APP_KEY
```bash
php artisan key:generate
```
Then update `APP_KEY` in Coolify environment variables.

#### Missing JWT_SECRET
```bash
php artisan jwt:secret
```
Then update `JWT_SECRET` in Coolify environment variables.

#### Storage Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

#### Run Migrations
```bash
php artisan migrate --force
```

### 3. Enable Debug Mode (Temporarily)

In Coolify environment variables, set:
```bash
APP_DEBUG=true
```

This will show detailed error messages. **Remember to set it back to `false` in production!**

### 4. Check Apache Error Logs
```bash
# In container
tail -f /var/log/apache2/error.log
```

### 5. Verify Environment Variables

Make sure these are set in Coolify:
- `APP_KEY` - Required!
- `APP_URL` - Your domain
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `JWT_SECRET` - Required for JWT auth

### 6. Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

If this fails, check:
- Database credentials
- Database host is accessible from container
- Firewall allows port 8968

### 7. Check PHP Errors
```bash
# Enable PHP error display (temporary)
php -i | grep display_errors
```

## Step-by-Step Debugging

1. **Check if container is running:**
   ```bash
   docker ps
   ```

2. **Enter the container:**
   ```bash
   docker exec -it <container_name> bash
   ```

3. **Check Laravel logs:**
   ```bash
   cat storage/logs/laravel.log
   ```

4. **Check Apache logs:**
   ```bash
   tail -f /var/log/apache2/error.log
   ```

5. **Test PHP:**
   ```bash
   php -v
   php -m  # List installed extensions
   ```

6. **Test database connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

7. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   ```

## Most Common Causes

1. **Missing APP_KEY** - Run `php artisan key:generate`
2. **Storage permissions** - Fix with chmod/chown commands above
3. **Database connection** - Check credentials and connectivity
4. **Missing JWT_SECRET** - Run `php artisan jwt:secret`
5. **Cache issues** - Clear all caches

## After Fixing

1. Clear all caches:
   ```bash
   php artisan optimize:clear
   ```

2. Cache for production:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Restart the container in Coolify

