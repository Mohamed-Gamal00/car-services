# Firebase Push Notifications Setup

This guide explains how to set up Firebase credentials for push notifications.

## Prerequisites

- Firebase project created at [Firebase Console](https://console.firebase.google.com/)
- Service account key generated

## Setup Instructions

### 1. Generate Firebase Service Account Key

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project
3. Click on the gear icon ⚙️ → Project Settings
4. Go to "Service Accounts" tab
5. Click "Generate New Private Key"
6. Download the JSON file

### 2. Place the Credentials File

Place the downloaded JSON file in one of these locations:

**Option 1: Public directory (recommended for Laravel)**
```
public/json/test-notification-3f882-dedabd83f76e.json
```

**Option 2: App directory**
```
app/json/test-notification-3f882-dedabd83f76e.json
```

### 3. Update the Path in Code (if needed)

If you use a different filename or location, update the path in:

**File:** `app/Http/Controllers/Dashboard/PushNotificationController.php`

```php
$credentialsFilePath = public_path('json/YOUR_FILENAME.json');
```

### 4. Security Notes

⚠️ **IMPORTANT SECURITY NOTES:**

1. **Never commit credentials to Git**
   - The `.gitignore` file already excludes these files
   - If accidentally committed, remove from Git history

2. **File Permissions**
   ```bash
   chmod 600 public/json/test-notification-3f882-dedabd83f76e.json
   ```

3. **Environment Variables (Alternative)**
   For better security, consider storing credentials in environment variables:
   
   ```env
   FIREBASE_CREDENTIALS_PATH=/path/to/credentials.json
   ```

### 5. Verify Setup

1. Go to `/dashboard/push_notification`
2. Fill in title and description
3. Click "إرسال تجريبي لي" (Send Test to Me)
4. You should receive a notification

## File Structure

```
project/
├── public/
│   └── json/                          # Firebase credentials (ignored by Git)
│       └── test-notification-*.json
├── app/
│   └── json/                          # Alternative location (ignored by Git)
│       └── test-notification-*.json
└── .gitignore                         # Excludes credentials
```

## Troubleshooting

### Error: "Firebase credentials file not found"

**Solution:** Ensure the file exists at the correct path:
```bash
ls -la public/json/
```

### Error: "Failed to retrieve access token"

**Solutions:**
1. Verify the JSON file is valid
2. Check file permissions
3. Ensure the service account has proper Firebase permissions

### Error: "Permission denied"

**Solution:** Fix file permissions:
```bash
chmod 600 public/json/test-notification-3f882-dedabd83f76e.json
```

## For Production Deployment

### Option 1: Manual Upload
Upload the credentials file to the server manually (not via Git)

### Option 2: Environment Variables
Store the entire JSON as an environment variable:

```env
FIREBASE_CREDENTIALS='{"type":"service_account","project_id":"..."}'
```

Then update the controller:
```php
$credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);
```

### Option 3: Secrets Management
Use a secrets management service like:
- AWS Secrets Manager
- HashiCorp Vault
- Laravel Forge Secrets

## Additional Resources

- [Firebase Cloud Messaging Documentation](https://firebase.google.com/docs/cloud-messaging)
- [Firebase Admin SDK Setup](https://firebase.google.com/docs/admin/setup)
- [Laravel Notifications Documentation](https://laravel.com/docs/notifications)

## Support

If you encounter issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console for JavaScript errors
3. Firebase Console for project status
