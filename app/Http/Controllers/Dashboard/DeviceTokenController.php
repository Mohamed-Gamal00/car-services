<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeviceTokenController extends Controller
{
    /**
     * Save Firebase device token for push notifications
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string|in:web,ios,android',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            // Delete any existing tokens with the same token value for other users
            DeviceToken::where('token', $request->token)
                ->where(function ($query) use ($admin) {
                    $query->where('tokenable_type', '!=', Admin::class)
                        ->orWhere('tokenable_id', '!=', $admin->id);
                })
                ->delete();

            // Update or create a token for the authenticated admin
            $deviceToken = DeviceToken::updateOrCreate(
                [
                    'tokenable_type' => Admin::class,
                    'tokenable_id' => $admin->id,
                    'token' => $request->token,
                ],
                [
                    'device_type' => $request->device_type ?? 'web',
                ]
            );

            Log::info('Device token saved successfully', [
                'admin_id' => $admin->id,
                'device_type' => $deviceToken->device_type,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Device token saved successfully',
                'data' => [
                    'id' => $deviceToken->id,
                    'device_type' => $deviceToken->device_type,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving device token', [
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save device token'
            ], 500);
        }
    }

    /**
     * Get all device tokens for the authenticated admin
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $tokens = $admin->deviceTokens()
            ->select('id', 'token', 'device_type', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tokens
        ]);
    }

    /**
     * Delete a specific device token
     */
    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $deleted = DeviceToken::where('id', $id)
                ->where('tokenable_type', Admin::class)
                ->where('tokenable_id', $admin->id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device token deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Device token not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting device token', [
                'admin_id' => $admin->id,
                'token_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete device token'
            ], 500);
        }
    }

}
