# Laravel Telescope - Request Tracing Guide

## 🔭 What is Telescope?

Laravel Telescope is an elegant debug assistant for Laravel applications. It provides insight into:
- Requests
- Exceptions
- Database queries
- Jobs
- Mail
- Notifications
- Cache operations
- Redis operations
- And much more!

## 🚀 Accessing Telescope

### Local Development
Visit: `http://your-app-url/telescope`

For example:
- `http://localhost/telescope`
- `http://127.0.0.1:8000/telescope`

### Features Available

1. **Requests Tab** 📨
   - View all HTTP requests
   - See request/response data
   - Check headers, cookies, session data
   - View request duration

2. **Exceptions Tab** ⚠️
   - See all exceptions and errors
   - Full stack traces
   - Exception context

3. **Queries Tab** 🗄️
   - All database queries
   - Query execution time
   - Slow query detection
   - Query bindings

4. **Jobs Tab** 👷
   - Background job execution
   - Job payload
   - Execution time

5. **Mail Tab** 📧
   - Emails sent
   - Email content preview
   - Recipients

6. **Notifications Tab** 🔔
   - All notifications sent
   - Notification channels
   - Notification data

7. **Logs Tab** 📝
   - Application logs
   - Log levels
   - Log context

## 🎯 How to Use for Debugging

### Debugging Payment Issues

1. **Access Telescope**: Go to `/telescope`

2. **Filter by Request**: 
   - Click on "Requests" tab
   - Look for your payment callback URL (e.g., `/api/payment/callback/QC-xxxxx`)
   - Click on the request to see details

3. **Check Request Data**:
   - Headers
   - Query parameters (payment ID)
   - Request body
   - Response status

4. **Check Queries**:
   - Click "Queries" tab
   - See what database queries were executed
   - Check for slow queries

5. **Check Exceptions**:
   - Click "Exceptions" tab
   - See if any errors occurred
   - View full stack trace

### Debugging Checkout Issues

1. Go to `/telescope`
2. Click "Requests" tab
3. Find your checkout request (`POST /api/checkout/1`)
4. Check:
   - Request payload
   - Validation errors
   - Database queries
   - Response data

### Debugging Invoice Generation

1. Go to `/telescope`
2. Click "Logs" tab
3. Search for "invoice" or "generateInvoicePDF"
4. Check error messages and context

## 🔍 Filtering and Searching

- **Search**: Use the search box to find specific requests
- **Filter by Status**: Click status codes (200, 404, 500, etc.)
- **Filter by Type**: Select specific watchers (Requests, Queries, etc.)
- **Time Range**: View requests from specific time periods

## 🛠️ Configuration

### Enable/Disable Telescope

In `.env`:
```env
TELESCOPE_ENABLED=true
```

### Change Telescope Path

In `.env`:
```env
TELESCOPE_PATH=admin/telescope
```

Then access at: `http://your-app-url/admin/telescope`

### Pruning Old Data

Telescope stores data in the database. To clean old entries:

```bash
php artisan telescope:prune --hours=48
```

This removes entries older than 48 hours.

### Schedule Automatic Pruning

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('telescope:prune')->daily();
}
```

## 📊 Performance Monitoring

### Slow Queries
1. Go to "Queries" tab
2. Sort by "Duration"
3. Identify slow queries (> 100ms)
4. Optimize with indexes or query improvements

### Slow Requests
1. Go to "Requests" tab
2. Sort by "Duration"
3. Identify slow endpoints
4. Check associated queries and jobs

## 🔒 Security Notes

- **Production**: Telescope is disabled by default in production
- **Access Control**: Only admins can access in production
- **Sensitive Data**: Passwords and tokens are hidden by default
- **Data Retention**: Old data is automatically pruned

## 💡 Tips

1. **Use Tags**: Tag important requests for easy filtering
2. **Watch Specific Users**: Filter by user ID
3. **Monitor API Calls**: Track external API requests
4. **Check Cache Hit Rate**: Optimize caching strategy
5. **Review Failed Jobs**: Identify and fix job failures

## 🐛 Common Issues

### Telescope Not Accessible
- Check `TELESCOPE_ENABLED=true` in `.env`
- Run `php artisan config:clear`
- Check you're accessing correct URL

### No Data Showing
- Make some requests to your app
- Check database connection
- Verify migrations ran: `php artisan migrate`

### Performance Issues
- Prune old data: `php artisan telescope:prune`
- Disable in production if not needed
- Limit watchers in `config/telescope.php`

## 📚 Resources

- [Official Documentation](https://laravel.com/docs/telescope)
- [GitHub Repository](https://github.com/laravel/telescope)

---

**Happy Debugging! 🎉**
