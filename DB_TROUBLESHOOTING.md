# Database Connection Troubleshooting

## Error: `SQLSTATE[HY000] [2002] Operation timed out`

This means your container cannot reach the MySQL database server.

## Step 1: Test Database Connection from Container

Run these commands in Coolify terminal or inside the container:

```bash
# Test if you can reach the database host
ping -c 3 168.231.110.172

# Test if the port is accessible
nc -zv 168.231.110.172 8968
# OR
telnet 168.231.110.172 8968

# Test MySQL connection directly
mysql -h 168.231.110.172 -P 8968 -u mysql -p
# Enter password when prompted
```

## Step 2: Check Database Server Configuration

The database server at `168.231.110.172:8968` might be:
1. **Not accessible from your container's network**
2. **Firewall blocking the connection**
3. **Only accepting connections from specific IPs**

### Solution A: If Database is in Coolify

If your database is also deployed in Coolify, use the **service name** instead of IP:

In Coolify environment variables, change:
```bash
DB_HOST=your-database-service-name  # Instead of IP address
```

### Solution B: Check Database Server Firewall

The database server needs to allow connections from your container's IP. You may need to:
1. Add your container's IP to the database server's allowed IPs
2. Or allow connections from the entire Coolify network

### Solution C: Use Database Service Name (If in Same Network)

If both services are in the same Docker network, use the service name:
```bash
DB_HOST=mysql-service-name
```

## Step 3: Temporary Workaround - Use File-Based Sessions/Cache

While fixing database connectivity, you can make the app work with file-based storage:

In Coolify environment variables, temporarily change:
```bash
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

This will allow your app to run without database connection for sessions/cache.

## Step 4: Verify Database Credentials

Double-check these in Coolify environment variables:
```bash
DB_CONNECTION=mysql
DB_HOST=168.231.110.172
DB_PORT=8968
DB_DATABASE=default
DB_USERNAME=mysql
DB_PASSWORD=NhUFwbcF6S7Noi7T1PvORJWM0J3FQCAhW7hOTvnmK5kbie1i9cnKNOcSH1juXokL
```

## Step 5: Test Connection with PHP

```bash
php artisan tinker
>>> try {
>>>     DB::connection()->getPdo();
>>>     echo "Connected successfully!";
>>> } catch (\Exception $e) {
>>>     echo "Connection failed: " . $e->getMessage();
>>> }
```

## Step 6: Check Network Configuration in Coolify

1. **Check if services are in the same network**
2. **Check Coolify's network settings**
3. **Verify database service is running and accessible**

## Common Solutions

### If Database is External (Not in Coolify)

1. **Check firewall rules** - Database server must allow connections from Coolify's network
2. **Check database bind address** - MySQL might be bound to `127.0.0.1` instead of `0.0.0.0`
3. **Check MySQL user permissions** - User must be allowed to connect from remote IPs

### If Database is in Coolify

1. **Use service name** instead of IP address
2. **Check both services are in the same network**
3. **Verify database service is running**

## Quick Test Script

Create a test file `test-db.php` in your project root:

```php
<?php
$host = '168.231.110.172';
$port = 8968;
$dbname = 'default';
$username = 'mysql';
$password = 'NhUFwbcF6S7Noi7T1PvORJWM0J3FQCAhW7hOTvnmK5kbie1i9cnKNOcSH1juXokL';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_TIMEOUT => 10,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
```

Run it:
```bash
php test-db.php
```

## Next Steps

1. **Test connectivity** using the commands above
2. **Check firewall/network** settings
3. **Verify database credentials** are correct
4. **Contact database administrator** if it's a managed database service

