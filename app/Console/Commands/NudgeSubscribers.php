<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\EveningPollenCheck;
use Carbon\Carbon;

class NudgeSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:nudge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a polite nudge to subscribers who have not logged an entry by their preferred notification time.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::whereHas('pushSubscriptions')->chunk(200, function ($users) {
            foreach ($users as $user) {

                $now = now()->timezone($user->timezone);
                $targetTime = Carbon::parse($user->notification_time, $user->timezone);

                // 1. Are we past their preferred start time?
                if ($now->greaterThanOrEqualTo($targetTime)) {

                    // 2. Did they already log an entry today?
                    $hasLoggedToday = $user->entries()->whereDate('date', $now->toDateString())->exists();
                    if (!$hasLoggedToday) {
                        // 3. Reset the daily counter if their last notification was not today
                        if (!$user->last_notified_at || !$user->last_notified_at->timezone($user->timezone)->isSameDay($now)) {
                            $user->update(['notification_attempts_today' => 0]);
                        }

                        // 4. Have we hit their personal limit?
                        if ($user->notification_attempts_today < $user->max_notification_attempts) {

                            $needsNudge = false;

                            // 5. If 0 attempts today, send immediately. Otherwise, wait 60 mins.
                            if ($user->notification_attempts_today === 0) {
                                $needsNudge = true;
                            } else {
                                $minutesSinceLast = $user->last_notified_at->diffInMinutes($now);
                                if ($minutesSinceLast >= 60) {
                                    $needsNudge = true;
                                }
                            }

                            // 6. Fire the notification and update trackers!
                            if ($needsNudge) {
                                $user->notify(new EveningPollenCheck());
                                $user->update([
                                    'last_notified_at' => now(),
                                    'notification_attempts_today' => $user->notification_attempts_today + 1
                                ]);
                            }
                        }
                    }
                }
            }
        });
    }
}
