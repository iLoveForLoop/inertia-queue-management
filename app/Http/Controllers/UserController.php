<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index($user=null)
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

public function requestQueue()
{
    $user = Auth::user();

    // Check if user already has a pending queue
    if ($user->queue && $user->queue->status === 'pending') {
        return redirect()->back()->with('error', 'You already have a pending queue.');
    }

    // Create new queue in transaction
    DB::transaction(function () use ($user) {
        // Get the next queue number
        $queueNumber = Queue::max('queue_number') + 1;

        // Delete any existing completed/canceled queue
        // if ($user->queue) {
        //     $user->queue->delete();
        // }

        // Create new queue
        Queue::create([
            'user_id' => $user->id,
            'queue_number' => $queueNumber,
            'status' => 'pending'
        ]);

        // Refresh the user relationship
        $user->load('queue');
    });
    $user->load('queue');
    // dd($user->queue->status);
    // Redirect back to the index which will show the new queue
    return redirect()->route('user', compact('user'));
}
}
