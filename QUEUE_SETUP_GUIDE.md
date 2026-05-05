# Queue Setup Guide

## Problem
Jobs are running synchronously (immediately) instead of being queued because `QUEUE_CONNECTION=sync` in `.env`.

## Solution

### Step 1: Update .env File
✅ **Already Done** - Changed `QUEUE_CONNECTION=sync` to `QUEUE_CONNECTION=database`

### Step 2: Create Jobs Table
Run this command to create the database table for storing queued jobs:

```bash
php artisan queue:table
php artisan migrate
```

This will create a `jobs` table in your database.

### Step 3: Create Failed Jobs Table (Optional but Recommended)
To track failed jobs:

```bash
php artisan queue:failed-table
php artisan migrate
```

### Step 4: Run the Queue Worker
To process queued jobs, you need to run the queue worker:

```bash
php artisan queue:work
```

**For Development:**
```bash
php artisan queue:work --tries=3 --timeout=90
```

**For Production (with Supervisor):**
Create a supervisor configuration file at `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
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

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Step 5: Test the Queue

After setting up, test by assigning a captain to an order:

1. Go to: http://127.0.0.1:8001/dashboard/orders/{id}/edit
2. Assign a captain
3. Check the `jobs` table in your database - you should see pending jobs
4. The queue worker will process them

### Step 6: Monitor Jobs in Telescope

Visit: http://127.0.0.1:8001/telescope/jobs

You should see:
- **Pending Jobs**: Jobs waiting to be processed
- **Processed Jobs**: Successfully completed jobs
- **Failed Jobs**: Jobs that failed (if any)

## Queue Commands Reference

```bash
# Start queue worker
php artisan queue:work

# Start queue worker with specific queue
php artisan queue:work --queue=high,default

# Process only one job
php artisan queue:work --once

# Restart queue workers gracefully
php artisan queue:restart

# Clear all jobs from queue
php artisan queue:clear

# Retry failed jobs
php artisan queue:retry all

# List failed jobs
php artisan queue:failed
```

## Jobs in Your Application

### 1. AssignCaptainToOrder
- **Purpose**: Automatically assigns available captains to paid orders
- **Triggered by**: 
  - PaymentController (after successful payment)
  - CaptainAssignmentService
  - MakeCaptainAvailableJob (when captain becomes available)

### 2. MakeCaptainAvailableJob
- **Purpose**: Makes captain available after service duration
- **Triggered by**: OrderController->assignCaptain()
- **Delayed**: Runs after service duration (e.g., 1 hour)

### 3. GenerateInvoiceJob (if exists)
- **Purpose**: Generates PDF invoices for orders
- **Triggered by**: After successful payment

## Troubleshooting

### Jobs not processing?
1. Make sure queue worker is running: `php artisan queue:work`
2. Check `jobs` table in database
3. Check logs: `storage/logs/laravel.log`
4. Check Telescope: http://127.0.0.1:8001/telescope/jobs

### Jobs failing?
1. Check failed jobs: `php artisan queue:failed`
2. View error details in Telescope
3. Retry failed job: `php artisan queue:retry {job-id}`
4. Retry all: `php artisan queue:retry all`

### Queue worker stopped?
- In development, restart manually: `php artisan queue:work`
- In production, supervisor will auto-restart

## Important Notes

⚠️ **Development**: 
- Run `php artisan queue:work` in a separate terminal
- Restart worker after code changes: `php artisan queue:restart`

⚠️ **Production**:
- Use Supervisor to keep queue worker running
- Monitor queue size and failed jobs
- Set up alerts for failed jobs

✅ **Benefits of Queue**:
- Faster response times (jobs run in background)
- Better user experience (no waiting)
- Retry failed jobs automatically
- Scale by adding more workers
