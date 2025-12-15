# Test Database Connection FROM CONTAINER

## Important: Test from INSIDE the container, not your local machine!

The container might be on a different network than your local machine.

## Step 1: Access Container Terminal in Coolify

1. Go to your application in Coolify
2. Click on "Terminal" or "Execute Command"
3. Or use SSH if available

## Step 2: Run These Tests FROM THE CONTAINER

### Test 1: Ping Database Host
```bash
ping -c 3 168.231.110.172
```

### Test 2: Check if Port 8968 is Accessible
```bash
nc -zv 168.231.110.172 8968
```

OR

```bash
timeout 5 bash -c "</dev/tcp/168.231.110.172/8968" && echo "Port is open" || echo "Port is closed"
```

### Test 3: Test MySQL Connection with PHP
```bash
php -r "
try {
    \$pdo = new PDO('mysql:host=168.231.110.172;port=8968;dbname=default', 'mysql', 'NhUFwbcF6S7Noi7T1PvORJWM0J3FQCAhW7hOTvnmK5kbie1i9cnKNOcSH1juXokL', [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo '✅ Connection successful!\n';
} catch (PDOException \$e) {
    echo '❌ Connection failed: ' . \$e->getMessage() . '\n';
}
"
```

### Test 4: Test with Laravel Artisan
```bash
php artisan tinker
>>> try {
>>>     DB::connection()->getPdo();
>>>     echo "✅ Database connected!";
>>> } catch (\Exception $e) {
>>>     echo "❌ Error: " . $e->getMessage();
>>> }
```

## Step 3: Check Container Network

```bash
# Check container's IP address
hostname -I

# Check network interfaces
ip addr show

# Check routing
ip route
```

## Common Issues and Solutions

### Issue 1: Container Can't Reach Database (Port Closed)

**Symptom:** `nc -zv` shows connection refused or timeout

**Solution:**
- Database server firewall is blocking Coolify's network
- Database server needs to allow connections from Coolify's IP range
- Contact database administrator to whitelist Coolify's network

### Issue 2: Database Server Not Accepting Remote Connections

**Symptom:** Connection times out

**Solution:**
- MySQL `bind-address` might be set to `127.0.0.1` (localhost only)
- Need to change to `0.0.0.0` or comment it out
- MySQL user needs `%` host permission (not just `localhost`)

### Issue 3: Database is in Different Network

**Symptom:** Can't ping database host

**Solution:**
- If database is also in Coolify, use service name instead of IP
- Check if both services are in the same Docker network
- Use internal network IP if available

## Quick Fix: Use Service Name (If Database is in Coolify)

If your database is also deployed in Coolify, change `DB_HOST` in environment variables:

```bash
# Instead of IP address
DB_HOST=your-database-service-name

# Example:
DB_HOST=mysql-db
```

## Alternative: Use Database URL

Some database services provide a connection URL. Check if your database provider gives you a connection string.

## Next Steps

1. **Run tests FROM THE CONTAINER** (not your local machine)
2. **Share the results** - especially the `nc -zv` and PHP PDO test results
3. **Check database server firewall** - needs to allow Coolify's network
4. **Verify database user permissions** - user must be allowed to connect from remote IPs

## If Tests Fail from Container

The database server at `168.231.110.172` needs to:
1. **Allow connections from Coolify's network IP range**
2. **Have MySQL bind-address set to `0.0.0.0`** (not `127.0.0.1`)
3. **Have MySQL user with `%` host** (allows remote connections)

Contact your database administrator to configure these settings.

