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
        //last completed queue number
        $lastCompleted = Queue::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->value('queue_number') ?? 0;

        // next pending queue to be served
        $currentlyServing = Queue::where('status', 'pending')
            ->where('queue_number', '>', $lastCompleted)
            ->orderBy('queue_number')
            ->value('queue_number') ?? $user->queue->queue_number;

        // check if this user is currently being served
        $isCurrentlyServing = $currentlyServing === $user->queue->queue_number;

        // count pending queues are ahead
        if (!$isCurrentlyServing) {
            $queuesAhead = Queue::where('status', 'pending')
                ->where('queue_number', '>', $lastCompleted)
                ->where('queue_number', '<', $user->queue->queue_number)
                ->count();
        }

        $waitTime = $queuesAhead * 2;
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


    if ($user->queue && $user->queue->status === 'pending') {
        return redirect()->back()->with('error', 'You already have a pending queue.');
    }


    DB::transaction(function () use ($user) {

        $queueNumber = Queue::max('queue_number') + 1;




        Queue::create([
            'user_id' => $user->id,
            'queue_number' => $queueNumber,
            'status' => 'pending'
        ]);


        $user->load('queue');
    });
    $user->load('queue');
    // dd($user->queue->status);

    return redirect()->route('user', compact('user'));
}

public function cancel(Queue $queue){

    $queue->update(['status' => 'canceled']);
    return redirect()->back()->with('error', 'You cancelled your queue');

}
}
