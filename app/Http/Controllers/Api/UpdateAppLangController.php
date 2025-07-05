<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateAppLangController extends Controller
{
//    public function updateLanguage(Request $request)
//    {
//        $request->validate([
//            'lang' => 'required|in:ar,en',
//        ]);
//        return $request->lang;
//        $user = $request->user();
//        $user->lang = $request->lang;
////        Log::info('response', [
////            'user' => $user,
////            'lang' => $request->lang
////        ]);
//        $user->save();
//
//        return response()->json([
//            'message' => 'Language updated successfully',
//            'lang' => $user->lang
//        ]);
//    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'lang' => 'required|in:ar,en',
        ]);

        // حدد المستخدم الحالي بناءً على الجارد
        $user = auth('user')->user() ?? auth('captain')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $user->lang = $request->lang;
        $user->save();

        return response()->json([
            'message' => 'success',
            'lang' => $user->lang
        ]);
    }


}
