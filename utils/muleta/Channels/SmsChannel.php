<?php

namespace Muleta\Channels;

use Illuminate\Notifications\Notification;
use MediaManager\Jobs\Sms;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        Log::debug(
            '[NotificationSms] Criando notificação'
        );
        // Send notification to the $notifiable instance...
        Sms::dispatch($message->to, $message->msg);
    }
}
