# Invoice Generation Queue Setup

## Problem Solved

Invoice generation was timing out during HTTP requests (payment callback). Moving it to a queue job solves this by:
- ✅ Preventing HTTP timeouts
- ✅ Making payment callback faster
- ✅ Allowing retries on failure
- ✅ Better error handling

## How It Works Now

### Before (Synchronous)
```
Payment Callback → Generate Invoice (slow, can timeout) → Return Response
```

### After (Asynchronous)
```
Payment Callback → Dispatch Job → Return Response (fast)
                        ↓
                   Queue Worker
                        ↓
                Generate Invoice (in background)
```

## Setup

### Option 1: Sync Queue (Simple, for Development)

Add to `.env`:
```env
QUEUE_CONNECTION=sync
```

This runs jobs immediately (synchronously) - good for development/testing.

### Option 2: Database Queue (Recommended for Production)

1. **Configure `.env`:**
```env
QUEUE_CONNECTION=database
```

2. **Create jobs table (if not exists):**
```bash
php artisan queue:table
php artisan migrate
```

3. **Run queue worker:**
```bash
# In a separate terminal, keep this running
php artisan queue:work --queue=invoices,default

# Or use supervisor in production (see below)
```

### Option 3: Redis Queue (Best Performance)

1. **Install Redis** (if not installed)

2. **Configure `.env`:**
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

3. **Run queue worker:**
```bash
php artisan queue:work redis --queue=invoices,default
```

## Testing

### Test Invoice Generation After Payment

1. **Start queue worker** (if using database/redis):
```bash
php artisan queue:work --queue=invoices,default
```

2. **Make a payment** and watch logs:
```bash
tail -f storage/logs/laravel.log | grep -i "invoice\|job"
```

3. **Check the job was dispatched:**
```
[INFO] Dispatching invoice generation job (order_id: 55)
[INFO] Invoice generation job dispatched (order_id: 55)
[INFO] Invoice generation job started (order_id: 55)
[INFO] Invoice PDF generated successfully
[INFO] Invoice generation job completed successfully
```

4. **Verify invoice was created:**
```bash
php artisan tinker --execute="echo Order::find(55)->invoice_url;"
```

### Manual Invoice Generation (Still Works)

```bash
# Regenerate invoice for specific order
php artisan invoice:regenerate ORDER_NUMBER
```

## Production Setup with Supervisor

For production, use Supervisor to keep queue worker running:

### 1. Install Supervisor
```bash
sudo apt-get install supervisor
```

### 2. Create Supervisor Config

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --queue=invoices,default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

### 3. Start Supervisor
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 4. Check Status
```bash
sudo supervisorctl status laravel-worker:*
```

## Monitoring

### Check Failed Jobs
```bash
php artisan queue:failed
```

### Retry Failed Jobs
```bash
# Retry all failed jobs
php artisan queue:retry all

# Retry specific job
php artisan queue:retry JOB_ID
```

### Clear Failed Jobs
```bash
php artisan queue:flush
```

## Benefits

### 1. No Timeouts
- Invoice generation runs in background
- Payment callback returns immediately
- No HTTP timeout issues

### 2. Automatic Retries
- Job retries 3 times on failure
- 120 second timeout per attempt
- Permanent failure logged

### 3. Better Performance
- Payment callback is faster
- User gets immediate response
- Invoice generated in background

### 4. Scalability
- Can run multiple workers
- Can prioritize invoice queue
- Can monitor job status

## Troubleshooting

### Invoice Not Generated?

1. **Check if queue worker is running:**
```bash
ps aux | grep "queue:work"
```

2. **Check failed jobs:**
```bash
php artisan queue:failed
```

3. **Check logs:**
```bash
tail -f storage/logs/laravel.log | grep invoice
```

4. **Manually regenerate:**
```bash
php artisan invoice:regenerate ORDER_NUMBER
```

### Queue Worker Stopped?

**Development:**
```bash
# Just restart it
php artisan queue:work --queue=invoices,default
```

**Production (with Supervisor):**
```bash
sudo supervisorctl restart laravel-worker:*
```

## Quick Start

### For Development (Simplest)

1. **Set sync queue in `.env`:**
```env
QUEUE_CONNECTION=sync
```

2. **Test payment** - invoice generates immediately

### For Production (Recommended)

1. **Set database queue in `.env`:**
```env
QUEUE_CONNECTION=database
```

2. **Create jobs table:**
```bash
php artisan queue:table
php artisan migrate
```

3. **Setup Supervisor** (see above)

4. **Start worker:**
```bash
sudo supervisorctl start laravel-worker:*
```

## Status

✅ Invoice generation moved to queue job
✅ Payment callback no longer times out
✅ Automatic retries on failure
✅ Better error handling and logging
✅ Manual regeneration still available

**Invoice generation is now reliable and won't block payment!** 🎉
