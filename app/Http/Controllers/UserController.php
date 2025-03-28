<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Queue;

class UserController extends Controller
{
    public function index()
{

    $user = Auth::user()->load('queue');

    if($user->hasRole('admin')){
        return redirect()->route('dashboard');
    }

    $waitTime = 0;
    $queuesAhead = 0;
    $currentlyServing = null;
    $isCurrentlyServing = false;

    if ($user->queue && $user->queue->status === 'pending') {
        // Get the last completed queue number
        $lastCompleted = Queue::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->value('queue_number') ?? 0;

        // Get the next pending queue to be served (lowest number after last completed)
        $currentlyServing = Queue::where('status', 'pending')
            ->where('queue_number', '>', $lastCompleted)
            ->orderBy('queue_number')
            ->value('queue_number') ?? $user->queue->queue_number;

        // Check if this user is currently being served
        $isCurrentlyServing = $currentlyServing === $user->queue->queue_number;

        // Count how many pending queues are ahead of this user
        if (!$isCurrentlyServing) {
            $queuesAhead = Queue::where('status', 'pending')
                ->where('queue_number', '>', $lastCompleted)
                ->where('queue_number', '<', $user->queue->queue_number)
                ->count();
        }

        $waitTime = $queuesAhead * 2; // 2 minutes per queue
    }

    return inertia('User/Index', [
        'user' => $user,
        'queuesAhead' => $queuesAhead,
        'waitTime' => $waitTime,
        'currentlyServing' => $currentlyServing,
        'isCurrentlyServing' => $isCurrentlyServing
    ]);
}
}
