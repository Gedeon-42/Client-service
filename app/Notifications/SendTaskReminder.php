<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class SendTaskReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $task, public $favoriteChanel = null)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($this->favoriteChanel) return [$this->favoriteChanel];

        return [OneSignalChannel::class, 'database'];
    }


    
    /**
     * Get the mail representation of the notification.
     */
    public function toOneSignal(object $notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject("Task Due Reminder for {$this->task->title}")
            ->setBody("
                {$this->task->due_date}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
