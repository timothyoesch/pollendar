<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    public function showAccountStep()
    {
        return Inertia::render('App/Onboarding/Account');
    }

    public function storeAccountStep(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'onboarding_step' => 2, // Track progress!
        ]);

        Auth::login($user);

        return redirect()->route('onboarding.pollen');
    }

    public function showPollenStep()
    {
        return Inertia::render('App/Onboarding/Pollen');
    }

    public function storePollenStep(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'allergies' => 'required|array',
            'location.name' => 'required|string',
            'location.lat' => 'required|numeric',
            'location.lon' => 'required|numeric',
        ]);

        $user->update([
            'pollen_allergies' => $validated['allergies'],
            'homebase_name' => $validated['location']['name'],
            'homebase_latitude' => $validated['location']['lat'],
            'homebase_longitude' => $validated['location']['lon'],
        ]);

        // $user->update([...]);
        $user->update(['onboarding_step' => 3]);

        return redirect()->route('onboarding.reminders');
    }

    public function showRemindersStep()
    {
        return Inertia::render('App/Onboarding/Reminders', [
            'timezones' => \App\Enums\Timezone::cases(),
        ]);
    }

    public function subscribeNotifications(Request $request)
    {
        $user = $request->user();

        $request->user()->updatePushSubscription(
        $request->endpoint,
        $request->keys['p256dh'],
        $request->keys['auth']
        );

        return response()->json(['success' => true]);
    }

    public function storeRemindersStep(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'remindersEnabled' => 'required|boolean',
            'notificationTime' => 'required_if:remindersEnabled,true|date_format:H:i',
            'timezone' => 'required_if:remindersEnabled,true|in:' . implode(',', array_map(fn($case) => $case->value, \App\Enums\Timezone::cases())),
            'maxNotificationAttempts' => 'required_if:remindersEnabled,true|integer|min:1',
        ]);

        if (!$validated['remindersEnabled']) {
            $user->update([
                'max_notification_attempts' => 0,
                'onboarding_step' => 4
            ]);
            return redirect()->route('dashboard');
        }

        $user->update([
            'notification_time' => $validated['notificationTime'],
            'timezone' => $validated['timezone'],
            'max_notification_attempts' => $validated['maxNotificationAttempts'],
            'onboarding_step' => 4
        ]);

        return redirect()->route('dashboard');
    }
}
