<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendTaskReminder;
use App\Services\OneSignalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function __construct(protected User $user) {}

    public function sendNotification()
    {
        $user = User::find(26);
        try {
        $user->notify(new SendTaskReminder($user));

        return response()->json([
            'message' => 'Notification sent'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to send notification',
            'error' => $e->getMessage()
        ], 500);
    }
    }

}
