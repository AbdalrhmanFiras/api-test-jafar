# Coolify Environment Variables Configuration

Copy and paste these environment variables into your Coolify project settings:

## Required Environment Variables

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=http://a04wg0wwccosgc4kk40kkwo8.168.231.110.172.sslip.io

DB_CONNECTION=mysql
DB_HOST=168.231.110.172
DB_PORT=8968
DB_DATABASE=default
DB_USERNAME=mysql
DB_PASSWORD=NhUFwbcF6S7Noi7T1PvORJWM0J3FQCAhW7hOTvnmK5kbie1i9cnKNOcSH1juXokL

JWT_SECRET=YOUR_JWT_SECRET_HERE
JWT_TTL=1440

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Steps to Deploy

1. **Set Environment Variables in Coolify:**
   - Go to your project settings in Coolify
   - Add all the environment variables above
   - **IMPORTANT:** Generate `APP_KEY` and `JWT_SECRET` (see below)

2. **Generate APP_KEY:**
   After deployment, run in Coolify terminal:
   ```bash
   php artisan key:generate
   ```
   Then copy the generated key and update `APP_KEY` in Coolify environment variables.

3. **Generate JWT_SECRET:**
   ```bash
   php artisan jwt:secret
   ```
   Then copy the generated secret and update `JWT_SECRET` in Coolify environment variables.

4. **Run Migrations:**
   ```bash
   php artisan migrate --force
   ```

5. **Set Permissions (if needed):**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

## Testing

1. **Health Check:**
   Visit: `http://a04wg0wwccosgc4kk40kkwo8.168.231.110.172.sslip.io/up`

2. **Test API:**
   ```bash
   curl http://a04wg0wwccosgc4kk40kkwo8.168.231.110.172.sslip.io/api/post
   ```

3. **Test Database Connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

## Troubleshooting

### Bad Gateway Error
- Check if Apache is running: `ps aux | grep apache`
- Check Apache logs in Coolify
- Verify port 80 is exposed correctly

### Database Connection Error
- Verify DB_HOST is accessible from container
- Check firewall rules for port 8968
- Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

### Permission Errors
- Run: `chmod -R 755 storage bootstrap/cache`
- Run: `chown -R www-data:www-data storage bootstrap/cache`

