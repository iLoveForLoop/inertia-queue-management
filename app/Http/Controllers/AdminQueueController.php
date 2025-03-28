<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminQueueController extends Controller
{
    public function index(Request $request)
    {
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

        // Get the last completed queue number to determine current serving queue
        $lastCompleted = Queue::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->value('queue_number') ?? 0;

        // Order queues with the next to be served (oldest pending) first,
        // then by queue number, and finally by update time
        $queues = $query->orderByRaw("
            CASE
                WHEN status = 'pending' AND queue_number > ? THEN 0
                WHEN status = 'pending' THEN 1
                WHEN status = 'completed' THEN 2
                ELSE 3
            END
        ", [$lastCompleted])
        ->orderBy('queue_number')
        ->orderBy('updated_at', 'desc')
        ->get();

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
        return redirect()->back()->with('success', 'Queue marked as completed.');
    }

    public function cancel(Queue $queue)
    {
        $queue->update(['status' => 'canceled']);
        return redirect()->back()->with('error', 'Queue canceled.');
    }
}
