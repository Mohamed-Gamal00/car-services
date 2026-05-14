# Device Token API Documentation

This API allows admins to manage their Firebase device tokens for push notifications.

## Endpoints

### 1. Store Device Token
**POST** `/dashboard/device-tokens`

Save or update a device token for the authenticated admin.

**Request Body:**
```json
{
    "token": "firebase_device_token_here",
    "device_type": "web" // optional: web, ios, android
}
```

**Response:**
```json
{
    "success": true,
    "message": "Device token saved successfully",
    "data": {
        "id": 1,
        "device_type": "web"
    }
}
```

**JavaScript Example:**
```javascript
$.ajax({
    url: '{{ route('device-tokens.store') }}',
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        token: firebaseToken,
        device_type: 'web'
    },
    success: function(response) {
        console.log('Token saved:', response);
    },
    error: function(error) {
        console.error('Error saving token:', error);
    }
});
```

---

### 2. Get All Device Tokens
**GET** `/dashboard/device-tokens`

Retrieve all device tokens for the authenticated admin.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "token": "firebase_token_1",
            "device_type": "web",
            "created_at": "2026-05-13T10:30:00.000000Z"
        },
        {
            "id": 2,
            "token": "firebase_token_2",
            "device_type": "android",
            "created_at": "2026-05-12T15:20:00.000000Z"
        }
    ]
}
```

**JavaScript Example:**
```javascript
$.ajax({
    url: '{{ route('device-tokens.index') }}',
    type: 'GET',
    success: function(response) {
        console.log('Device tokens:', response.data);
    }
});
```

---

### 3. Delete Specific Device Token
**DELETE** `/dashboard/device-tokens/{id}`

Delete a specific device token by ID.

**Response:**
```json
{
    "success": true,
    "message": "Device token deleted successfully"
}
```

**JavaScript Example:**
```javascript
$.ajax({
    url: '{{ route('device-tokens.destroy', ['id' => 1]) }}',
    type: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
        console.log('Token deleted:', response);
    }
});
```

---

### 4. Delete All Device Tokens
**DELETE** `/dashboard/device-tokens`

Delete all device tokens for the authenticated admin.

**Response:**
```json
{
    "success": true,
    "message": "Deleted 3 device token(s) successfully",
    "count": 3
}
```

**JavaScript Example:**
```javascript
$.ajax({
    url: '{{ route('device-tokens.destroy-all') }}',
    type: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
        console.log('All tokens deleted:', response);
    }
});
```

---

## Legacy Route (Backward Compatibility)

**POST** `/dashboard/save-token`

This route still works and points to the same `store` action.

---

## Firebase Integration Example

Complete example of Firebase integration with device token storage:

```html
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script>
// Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyC3tzyv__3udwxPWI5swk12qoGZ4J5sb1c",
    authDomain: "test-notification-3f882.firebaseapp.com",
    projectId: "test-notification-3f882",
    storageBucket: "test-notification-3f882.firebasestorage.app",
    messagingSenderId: "786873585382",
    appId: "1:786873585382:web:fe4db5ece2173b8eaaf527",
    measurementId: "G-31WKBC1QJW"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Request permission and save token
function initFirebaseMessagingRegistration() {
    messaging.requestPermission()
        .then(function() {
            return messaging.getToken();
        })
        .then(function(token) {
            console.log('Firebase Token:', token);
            
            // Save token to backend
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '{{ route('device-tokens.store') }}',
                type: 'POST',
                data: {
                    token: token,
                    device_type: 'web'
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log('Token saved successfully:', response);
                },
                error: function(err) {
                    console.error('Error saving token:', err);
                }
            });
        })
        .catch(function(err) {
            console.error('Firebase permission error:', err);
        });
}

// Handle foreground messages
messaging.onMessage(function(payload) {
    console.log('Message received:', payload);
    
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon
    };
    
    new Notification(noteTitle, noteOptions);
});

// Initialize on page load
document.addEventListener("DOMContentLoaded", function() {
    initFirebaseMessagingRegistration();
});
</script>
```

---

## Database Structure

Device tokens are stored in the `device_tokens` table with the following structure:

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| tokenable_type | string | Model type (App\Models\Admin) |
| tokenable_id | bigint | Admin ID |
| token | string | Firebase device token |
| device_type | string | Device type (web, ios, android) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

---

## Features

✅ **Polymorphic Relationship**: Works with Admin, User, and Captain models
✅ **Duplicate Prevention**: Automatically removes duplicate tokens
✅ **Multiple Devices**: Admins can have multiple device tokens
✅ **Device Type Tracking**: Track which device type each token belongs to
✅ **Secure**: Only authenticated admins can manage their own tokens
✅ **Logging**: All operations are logged for debugging
✅ **Error Handling**: Comprehensive error handling with meaningful messages

---

## Usage in Notifications

To send notifications to an admin:

```php
use App\Models\Admin;

$admin = Admin::find(1);
$tokens = $admin->deviceTokens->pluck('token')->toArray();

// Use $tokens array to send Firebase notifications
```
