<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\OnboardingController;
use App\Models\PollenForecast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::get('/sign-up', function () {
    return redirect()->route('onboarding.account');
});


Route::middleware([])->group(function () {
    Route::get('/onboarding/account', [OnboardingController::class, 'showAccountStep'])->name('onboarding.account');
    Route::post('/onboarding/account', [OnboardingController::class, 'storeAccountStep']);
});


Route::middleware('auth')->group(function () {
    Route::get('/onboarding/pollen', [OnboardingController::class, 'showPollenStep'])->name('onboarding.pollen');
    Route::post('/onboarding/pollen', [OnboardingController::class, 'storePollenStep']);

    Route::get('/onboarding/reminders', [OnboardingController::class, 'showRemindersStep'])->name('onboarding.reminders');
    Route::post('/onboarding/reminders', [OnboardingController::class, 'storeRemindersStep']);

    Route::post("/onboarding/skip-reminders", [OnboardingController::class, 'skipReminders'])->name('onboarding.skip-reminders');
    Route::post("/onboarding/push-subscription", [OnboardingController::class, 'subscribeNotifications'])->name('onboarding.subscribe-notifications');
});

Route::middleware('auth')->prefix('app')->group(function () {
    Route::get('/', function () {
        return Inertia::render('App/Dashboard', [
            'entries' => auth()->user()->entries()->with('pollenForecast')->orderBy("date", "desc")->get(),
            "todays_forecast" => PollenForecast::getForLatAndLong(
                now()->toDateString(),
                auth()->user()->homebase_latitude,
                auth()->user()->homebase_longitude
            )
        ]);
    })->name('app.dashboard');

    Route::get('/profile', function () {
        return Inertia::render('App/Profile');
    })->name('app.profile');

    Route::get('/entry/create', [EntryController::class, 'create'])->name('app.entry.create');
    Route::post('/entry/create', [EntryController::class, 'store'])->name('app.entry.store');
});
