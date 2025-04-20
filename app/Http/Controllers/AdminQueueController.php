<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Notifications\QueueNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminQueueController extends Controller
{
    public function index(Request $request)
    {

        if(Auth::user()->hasRole('user')){
            return redirect()->route('user');
        }

        $query = Queue::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('queue_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }


        $lastCompleted = Queue::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->value('queue_number') ?? 0;

        // Order queues with the next to be served (oldest pending) first,
        // then by queue number, and finally by update time
        // $queues = $query->orderByRaw("
        //     CASE
        //         WHEN status = 'pending' AND queue_number > ? THEN 0
        //         WHEN status = 'pending' THEN 1
        //         WHEN status = 'completed' THEN 2
        //         ELSE 3
        //     END
        // ", [$lastCompleted])
        // ->orderBy('updated_at', 'desc')
        // ->orderBy('queue_number')
        // ->get();

        $queues = $query->orderBy('updated_at', 'desc')
        ->orderBy('queue_number')
        ->paginate(5);

        $stats = [
            'pending' => Queue::where('status', 'pending')->count(),
            'completed' => Queue::where('status', 'completed')->count(),
            'canceled' => Queue::where('status', 'canceled')->count(),
            'total' => Queue::count(),
        ];

        return Inertia::render('Dashboard', [
            'queues' => $queues,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function complete(Queue $queue)
    {

        $queue->update(['status' => 'completed']);
        $queue->load('user');
        $queue->user->notify(new QueueNotif($queue->id));
        return redirect()->back()->with('success', 'Queue marked as completed.');
    }

    public function cancel(Queue $queue)
    {

        $queue->update(['status' => 'canceled']);
        $queue->load('user');
        $queue->user->notify(new QueueNotif($queue->id));
        return redirect()->back()->with('error', 'Queue canceled.');
    }

    public function sendQueueServing(Queue $queue=null){

        // dd('here');

        $queue->load('user');
        $queue->user->notify(new QueueNotif($queue->id));
    }
}
