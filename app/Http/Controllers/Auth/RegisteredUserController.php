<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\User;
use App\Notifications\CodeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if(!$request->isVerified){
            $verificationCode = rand(100000, 999999);

            Notification::route('mail', $request->email)
            ->notify(new CodeNotification($verificationCode));

            return back()->with('info', (string) $verificationCode);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');




        if (!$user->queue) {
            $queueNumber = Queue::max('queue_number') + 1;

            $user->queue()->create([
                'queue_number' => $queueNumber,

            ]);

        }

        event(new Registered($user));


        Auth::login($user);

        return redirect()->route('user');
    }

}
