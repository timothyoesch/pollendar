<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class EveningPollenCheck extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Sniffle Check! 🤧')
            ->icon('/img/logo_notifier_fallback.png')
            ->body('How are your allergies doing today? Tap to log your symptoms.')
            ->action('Log Symptoms', 'open_app');
    }
}
