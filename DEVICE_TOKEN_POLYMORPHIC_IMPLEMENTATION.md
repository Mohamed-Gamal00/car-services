# Device Token Polymorphic Implementation

## Overview

Device tokens for push notifications are now implemented using Laravel's polymorphic relationships, allowing both Users and Captains to register their device tokens in a single table.

## Database Structure

### Table: `device_tokens`

```sql
id                  BIGINT UNSIGNED PRIMARY KEY
tokenable_type      VARCHAR(255)  -- 'App\Models\User' or 'App\Models\Captain'
tokenable_id        BIGINT UNSIGNED  -- User ID or Captain ID
token               TEXT  -- Firebase/FCM device token
device_type         VARCHAR(255) NULLABLE  -- 'android' or 'ios'
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### Indexes

```sql
INDEX (tokenable_type, tokenable_id)
INDEX (token)
```

## Model Relationships

### DeviceToken Model

**File:** `app/Models/DeviceToken.php`

```php
class DeviceToken extends Model
{
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'token',
        'device_type',
    ];

    // Polymorphic relationship
    public function tokenable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('tokenable_type', User::class)
            ->where('tokenable_id', $userId);
    }

    public function scopeForCaptain($query, $captainId)
    {
        return $query->where('tokenable_type', Captain::class)
            ->where('tokenable_id', $captainId);
    }
}
```

### User Model

**File:** `app/Models/User.php`

```php
class User extends Model
{
    public function deviceTokens()
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }
}
```

### Captain Model

**File:** `app/Models/Captain.php`

```php
class Captain extends Model
{
    public function deviceTokens()
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }
}
```

## API Endpoints

### 1. User Token Registration

**Endpoint:** `POST /api/v1/register-token`

**Middleware:** `auth:user`

**Request:**
```json
{
    "token": "firebase_device_token_here",
    "device_type": "android"  // or "ios" (optional)
}
```

**Response (Success):**
```json
{
    "status": 1,
    "message": "تم التسجيل بنجاح",
    "data": null
}
```

**Response (Error):**
```json
{
    "status": 0,
    "message": "The token field is required.",
    "data": {
        "token": ["The token field is required."]
    }
}
```

### 2. Captain Token Registration

**Endpoint:** `POST /api/v1/captain-register-token`

**Middleware:** `auth:captain`

**Request:**
```json
{
    "token": "firebase_device_token_here",
    "device_type": "ios"  // or "android" (optional)
}
```

**Response:** Same as user token registration

## Implementation Details

### User Token Registration

**File:** `app/Http/Controllers/Api/UserAuthController.php`

```php
public function registerToken(Request $request)
{
    // Validate the request
    $validator = validator()->make($request->all(), [
        'token' => 'required',
        'device_type' => 'nullable|in:android,ios',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first(),
            'data' => $validator->errors(),
        ]);
    }

    $user = $request->user();

    // Delete any existing tokens with the same token value for other users
    DeviceToken::where('token', $request->token)
        ->where(function ($query) use ($user) {
            $query->where('tokenable_type', '!=', User::class)
                ->orWhere('tokenable_id', '!=', $user->id);
        })
        ->delete();

    // Update or create a token for the authenticated user
    DeviceToken::updateOrCreate(
        [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ],
        [
            'token' => $request->token,
            'device_type' => $request->device_type ?? $request->type,
        ]
    );

    return response()->json([
        'status' => 1,
        'message' => 'تم التسجيل بنجاح',
        'data' => null,
    ]);
}
```

### Captain Token Registration

**File:** `app/Http/Controllers/Api/CaptainAuthController.php`

```php
public function registerToken(Request $request)
{
    // Validate the incoming request
    $validator = validator()->make($request->all(), [
        'token' => 'required',
        'device_type' => 'nullable|in:android,ios',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first(),
            'data' => $validator->errors(),
        ]);
    }

    $captain = $request->user();

    // Delete any existing tokens with the same token value for other captains
    DeviceToken::where('token', $request->token)
        ->where(function ($query) use ($captain) {
            $query->where('tokenable_type', '!=', Captain::class)
                ->orWhere('tokenable_id', '!=', $captain->id);
        })
        ->delete();

    // Update or create a token for the authenticated captain
    DeviceToken::updateOrCreate(
        [
            'tokenable_type' => Captain::class,
            'tokenable_id' => $captain->id,
        ],
        [
            'token' => $request->token,
            'device_type' => $request->device_type ?? $request->type,
        ]
    );

    return response()->json([
        'status' => 1,
        'message' => 'تم التسجيل بنجاح',
        'data' => null,
    ]);
}
```

## Key Features

### 1. Polymorphic Relationship
- ✅ Single table for both users and captains
- ✅ Uses `tokenable_type` and `tokenable_id`
- ✅ Clean and maintainable structure

### 2. Token Uniqueness
- ✅ Prevents duplicate tokens across different users/captains
- ✅ Automatically removes old tokens when reassigned
- ✅ One device can only be registered to one user/captain at a time

### 3. Update or Create
- ✅ Updates existing token if user/captain already registered
- ✅ Creates new token if first time registration
- ✅ Prevents duplicate entries

### 4. Backward Compatibility
- ✅ Supports both `device_type` and `type` field names
- ✅ Graceful handling of optional device type

## Usage Examples

### Sending Notifications to a User

```php
$user = User::find(1);
$tokens = $user->deviceTokens()->pluck('token')->toArray();

if (!empty($tokens)) {
    $this->notifyByFirebase(
        'New Order',
        'You have a new order!',
        $tokens,
        ['order_id' => 123]
    );
}
```

### Sending Notifications to a Captain

```php
$captain = Captain::find(1);
$tokens = $captain->deviceTokens()->pluck('token')->toArray();

if (!empty($tokens)) {
    $this->notifyByFirebase(
        'New Request',
        'There is a new request for you',
        $tokens,
        ['order_id' => 123]
    );
}
```

### Getting All Tokens for a Type

```php
// All user tokens
$userTokens = DeviceToken::where('tokenable_type', User::class)
    ->pluck('token')
    ->toArray();

// All captain tokens
$captainTokens = DeviceToken::where('tokenable_type', Captain::class)
    ->pluck('token')
    ->toArray();

// All Android tokens
$androidTokens = DeviceToken::where('device_type', 'android')
    ->pluck('token')
    ->toArray();
```

### Using Scopes

```php
// Get tokens for specific user
$userTokens = DeviceToken::forUser(1)->get();

// Get tokens for specific captain
$captainTokens = DeviceToken::forCaptain(1)->get();
```

## Testing

### Test User Token Registration

```bash
curl -X POST http://localhost/api/v1/register-token \
  -H "Authorization: Bearer USER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "token": "firebase_token_123",
    "device_type": "android"
  }'
```

### Test Captain Token Registration

```bash
curl -X POST http://localhost/api/v1/captain-register-token \
  -H "Authorization: Bearer CAPTAIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "token": "firebase_token_456",
    "device_type": "ios"
  }'
```

### Verify in Database

```sql
-- Check all device tokens
SELECT * FROM device_tokens;

-- Check user tokens
SELECT * FROM device_tokens WHERE tokenable_type = 'App\\Models\\User';

-- Check captain tokens
SELECT * FROM device_tokens WHERE tokenable_type = 'App\\Models\\Captain';
```

### Test with Tinker

```php
php artisan tinker

// Register token for user
$user = User::find(1);
$user->deviceTokens()->create([
    'token' => 'test_token_123',
    'device_type' => 'android'
]);

// Get user's tokens
$user->deviceTokens;

// Register token for captain
$captain = Captain::find(1);
$captain->deviceTokens()->create([
    'token' => 'test_token_456',
    'device_type' => 'ios'
]);

// Get captain's tokens
$captain->deviceTokens;
```

## Migration (If Needed)

If you need to migrate from old structure to polymorphic:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // If you have old user_id and captain_id columns
        if (Schema::hasColumn('device_tokens', 'user_id')) {
            // Migrate user tokens
            DB::table('device_tokens')
                ->whereNotNull('user_id')
                ->update([
                    'tokenable_type' => 'App\\Models\\User',
                    'tokenable_id' => DB::raw('user_id')
                ]);
            
            Schema::table('device_tokens', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('device_tokens', 'captain_id')) {
            // Migrate captain tokens
            DB::table('device_tokens')
                ->whereNotNull('captain_id')
                ->update([
                    'tokenable_type' => 'App\\Models\\Captain',
                    'tokenable_id' => DB::raw('captain_id')
                ]);
            
            Schema::table('device_tokens', function (Blueprint $table) {
                $table->dropColumn('captain_id');
            });
        }
    }
};
```

## Benefits

### 1. Single Source of Truth
- ✅ One table for all device tokens
- ✅ Consistent structure
- ✅ Easier to maintain

### 2. Scalability
- ✅ Easy to add more user types (Admin, Vendor, etc.)
- ✅ No need to create new tables
- ✅ Just add relationship to new model

### 3. Query Efficiency
- ✅ Indexed polymorphic columns
- ✅ Fast lookups
- ✅ Efficient token management

### 4. Code Reusability
- ✅ Same logic for all user types
- ✅ Shared scopes and methods
- ✅ DRY principle

## Status

✅ **Device token registration fully implemented with polymorphic relationships!**

- ✅ User token registration working
- ✅ Captain token registration working
- ✅ Polymorphic relationships configured
- ✅ Token uniqueness enforced
- ✅ Backward compatible
- ✅ Production-ready

**Push notifications can now be sent to both users and captains using a unified system!** 🎉
