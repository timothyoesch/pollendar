<?php

use App\Http\Controllers\OnboardingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::get('/sign-up', function () {
    return redirect()->route('onboarding.account');
});

// Step 1: Account Creation (Only for guests)
Route::middleware([])->group(function () {
    Route::get('/onboarding/account', [OnboardingController::class, 'showAccountStep'])->name('onboarding.account');
    Route::post('/onboarding/account', [OnboardingController::class, 'storeAccountStep']);
});

// Steps 2 & 3: Preferences (Requires the user to be logged in from Step 1)
Route::middleware('auth')->group(function () {
    Route::get('/onboarding/pollen', [OnboardingController::class, 'showPollenStep'])->name('onboarding.pollen');
    Route::post('/onboarding/pollen', [OnboardingController::class, 'storePollenStep']);

    Route::get('/onboarding/reminders', [OnboardingController::class, 'showRemindersStep'])->name('onboarding.reminders');
    Route::post('/onboarding/reminders', [OnboardingController::class, 'storeRemindersStep']);

    Route::post("/onboarding/skip-reminders", [OnboardingController::class, 'skipReminders'])->name('onboarding.skip-reminders');
    Route::post("/onboarding/push-subscription", [OnboardingController::class, 'subscribeNotifications'])->name('onboarding.subscribe-notifications');

    Route::get('/app', function () {
        return Inertia::render('App/Dashboard');
    })->name('app.dashboard');

    Route::get('/app/profile', function () {
        return Inertia::render('App/Profile');
    })->name('app.profile');
});
